<?php

namespace Botble\Base\Exceptions;

use App\Exceptions\Handler as ExceptionHandler;
use Botble\Base\Http\Responses\BaseHttpResponse;
use EmailHandler;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Concerns\InteractsWithContentTypes;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use League\OAuth2\Server\Exception\OAuthServerException;
use Log;
use RvMedia;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use URL;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     * @param \Illuminate\Http\Request $request
     * @param Exception $ex
     * @return BaseHttpResponse|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Response
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function render($request, Exception $ex)
    {
        if ($ex instanceof PostTooLargeException) {
            return RvMedia::responseError(trans('media::media.upload_failed', [
                'size' => human_file_size(RvMedia::getServerConfigMaxUploadFileSize()),
            ]));
        }

        if ($ex instanceof ModelNotFoundException && $request->expectsJson()) {
            return (new BaseHttpResponse)
                ->setError()
                ->setMessage('Not found')
                ->setCode(404)
                ->toResponse($request);
        }

        if ($ex instanceof ModelNotFoundException || $ex instanceof MethodNotAllowedHttpException) {
            $ex = new NotFoundHttpException($ex->getMessage(), $ex);
        }

        if ($ex instanceof AuthorizationException) {
            $response = $this->handleResponseData(403, $request);
            if ($response) {
                return $response;
            }
        }

        if ($this->isHttpException($ex) && !app()->isDownForMaintenance()) {
            $code = $ex->getStatusCode();

            do_action(BASE_ACTION_SITE_ERROR, $code);

            if (in_array($code, [401, 403, 404, 500, 503])) {
                $response = $this->handleResponseData($code, $request);
                if ($response) {
                    return $response;
                }
            }
        } elseif (app()->isDownForMaintenance() && view()->exists('theme.' . setting('theme') . '::views.503')) {
            return response()->view('theme.' . setting('theme') . '::views.503', ['exception' => $ex], 503);
        }

        return parent::render($request, $ex);
    }

    /**
     * @param integer $code
     * @param Request|InteractsWithContentTypes $request
     * @return bool|BaseHttpResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function handleResponseData($code, $request)
    {
        if ($request->expectsJson()) {
            if (function_exists('admin_bar')) {
                admin_bar()->setIsDisplay(false);
            }

            if ($code == 401) {
                return (new BaseHttpResponse)
                    ->setError()
                    ->setMessage(trans('core/acl::permissions.access_denied_message'))
                    ->setCode($code)
                    ->toResponse($request);
            }

            if ($code == 403) {
                return (new BaseHttpResponse)
                    ->setError()
                    ->setMessage(__('This action is unauthorized.'))
                    ->setCode($code)
                    ->toResponse($request);
            }
        }

        $code = (string)$code;
        $code = $code == '403' ? '401' : $code;
        $code = $code == '503' ? '500' : $code;

        if ($request->is(config('core.base.general.admin_dir') . '/*') || $request->is(config('core.base.general.admin_dir'))) {
            return response()->view('core/base::errors.' . $code, [], $code);
        }

        if (view()->exists('theme.' . setting('theme') . '::views.' . $code)) {
            return response()->view('theme.' . setting('theme') . '::views.' . $code, [], $code);
        }

        return false;
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Emails.
     *
     * @param  \Exception $exception
     * @return void
     * @throws \Monolog\Handler\MissingExtensionException
     * @throws \Throwable
     */
    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception) && !$this->isExceptionFromBot()) {
            if (!app()->isLocal() && !app()->runningInConsole()) {
                if (setting('enable_send_error_reporting_via_email', false) && setting('email_driver', config('mail.driver'))) {
                    EmailHandler::sendErrorException($exception);
                }

                if (config('core.base.general.error_reporting.via_slack', false) == true && !$exception instanceof OAuthServerException) {
                    config()->set([
                        'logging.channels.slack.username' => 'Botble BOT',
                        'logging.channels.slack.emoji'    => ':helmet_with_white_cross:',
                    ]);

                    Log::channel('slack')
                        ->critical(URL::full() . "\n" . $exception->getFile() . ':' . $exception->getLine() . "\n" . $exception->getMessage());
                }
            }
        }

        parent::report($exception);
    }

    /**
     * Determine if the exception is from the bot.
     *
     * @return boolean
     */
    protected function isExceptionFromBot()
    {
        $ignored_bots = config('core.base.general.error_reporting.ignored_bots', []);
        $agent = array_key_exists('HTTP_USER_AGENT', $_SERVER) ? strtolower($_SERVER['HTTP_USER_AGENT']) : null;

        if (empty($agent)) {
            return false;
        }

        foreach ($ignored_bots as $bot) {
            if ((strpos($agent, $bot) !== false)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request|InteractsWithContentTypes $request
     * @param  \Illuminate\Auth\AuthenticationException $exception
     * @return BaseHttpResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return (new BaseHttpResponse())
                ->setError()
                ->setMessage($exception->getMessage())
                ->setCode(401)
                ->toResponse($request);
        }

        return redirect()->guest(route('access.login'));
    }
}
