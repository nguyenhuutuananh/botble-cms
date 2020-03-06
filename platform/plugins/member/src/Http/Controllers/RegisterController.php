<?php

namespace Botble\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Member\Models\Member;
use Botble\Member\Repositories\Interfaces\MemberInterface;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SeoHelper;
use URL;
use Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = null;

    /**
     * @var MemberInterface
     */
    protected $memberRepository;

    /**
     * Create a new controller instance.
     *
     * @param MemberInterface $memberRepository
     *
     */
    public function __construct(MemberInterface $memberRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->redirectTo = route('public.member.register');
    }

    /**
     * Show the application registration form.
     *
     * @return \Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     */
    public function showRegistrationForm()
    {
        SeoHelper::setTitle(__('Register'));

        return view('plugins/member::auth.register');
    }

    /**
     * Confirm a user with a given confirmation code.
     *
     * @param $email
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param MemberInterface $memberRepository
     * @return BaseHttpResponse
     *
     */
    public function confirm($email, Request $request, BaseHttpResponse $response, MemberInterface $memberRepository)
    {
        if (!URL::hasValidSignature($request)) {
            abort(404);
        }

        $member = $memberRepository->getFirstBy(['email' => $email]);

        if (!$member) {
            abort(404);
        }

        $member->confirmed_at = Carbon::now(config('app.timezone'));
        $this->memberRepository->createOrUpdate($member);

        $this->guard()->login($member);

        return $response
            ->setNextUrl(route('public.member.dashboard'))
            ->setMessage(trans('plugins/member::member.confirmation_successful'));
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     *
     */
    protected function guard()
    {
        return Auth::guard('member');
    }

    /**
     * Resend a confirmation code to a user.
     *
     * @param  \Illuminate\Http\Request $request
     * @param MemberInterface $memberRepository
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     *
     */
    public function resendConfirmation(Request $request, MemberInterface $memberRepository, BaseHttpResponse $response)
    {
        $member = $memberRepository->getFirstBy(['email' => $request->input('email')]);
        if (!$member) {
            return $response
                ->setError()
                ->setMessage(__('Cannot find this account!'));
        }

        $this->sendConfirmationToUser($member);

        return $response
            ->setMessage(trans('plugins/member::member.confirmation_resent'));
    }

    /**
     * Send the confirmation code to a user.
     *
     * @param Member $member
     *
     */
    protected function sendConfirmationToUser($member)
    {
        // Notify the user
        $notification = app(config('plugins.member.general.notification'));
        $member->notify($notification);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     *
     */
    public function register(Request $request, BaseHttpResponse $response)
    {
        $this->validator($request->input())->validate();

        event(new Registered($member = $this->create($request->input())));

        if (config('plugins.member.general.verify_email', true)) {
            $this->sendConfirmationToUser($member);
            return $this->registered($request, $member)
                ?: $response->setNextUrl($this->redirectPath())->setMessage(trans('plugins/member::member.confirmation_info'));
        }

        $member->confirmed_at = Carbon::now(config('app.timezone'));
        $this->memberRepository->createOrUpdate($member);
        $this->guard()->login($member);
        return $this->registered($request, $member)
            ?: $response->setNextUrl($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     *
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:120',
            'last_name'  => 'required|max:120',
            'email'      => 'required|email|max:255|unique:members',
            'password'   => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return Member
     *
     */
    protected function create(array $data)
    {
        return $this->memberRepository->create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => bcrypt($data['password']),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getVerify()
    {
        return view('plugins/member::auth.verify');
    }
}
