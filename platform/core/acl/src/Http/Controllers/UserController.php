<?php

namespace Botble\ACL\Http\Controllers;

use Assets;
use Auth;
use Botble\ACL\Forms\PasswordForm;
use Botble\ACL\Forms\ProfileForm;
use Botble\ACL\Forms\UserForm;
use Botble\ACL\Tables\UserTable;
use Botble\ACL\Http\Requests\ChangeProfileImageRequest;
use Botble\ACL\Http\Requests\CreateUserRequest;
use Botble\ACL\Http\Requests\UpdatePasswordRequest;
use Botble\ACL\Http\Requests\UpdateProfileRequest;
use Botble\ACL\Models\User;
use Botble\ACL\Models\UserMeta;
use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\ACL\Services\ChangePasswordService;
use Botble\ACL\Services\CreateUserService;
use Botble\ACL\Services\UpdateProfileImageService;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Exception;
use Illuminate\Http\Request;

class UserController extends BaseController
{

    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * @var RoleInterface
     */
    protected $roleRepository;

    /**
     * UserController constructor.
     * @param UserInterface $userRepository
     * @param RoleInterface $roleRepository
     */
    public function __construct(
        UserInterface $userRepository,
        RoleInterface $roleRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display all users
     * @param UserTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(UserTable $dataTable)
    {
        page_title()->setTitle(trans('core/acl::permissions.user_management'));

        Assets::addScripts(['bootstrap-editable'])
            ->addStyles(['bootstrap-editable'])
            ->addAppModule(['user']);

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        page_title()->setTitle(__('Create new user'));

        return $formBuilder->create(UserForm::class)->renderForm();
    }

    /**
     * @param CreateUserRequest $request
     * @param CreateUserService $service
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCreate(CreateUserRequest $request, CreateUserService $service, BaseHttpResponse $response)
    {
        $user = $service->execute($request);

        event(new CreatedContentEvent(USER_MODULE_SCREEN_NAME, $request, $user));

        return $response
            ->setPreviousUrl(route('users.list'))
            ->setNextUrl(route('user.profile.view', $user->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function getDelete($id, Request $request, BaseHttpResponse $response)
    {
        if (Auth::user()->getKey() == $id) {
            return $response
                ->setError()
                ->setMessage(trans('core/acl::users.delete_user_logged_in'));
        }

        try {
            $user = $this->userRepository->findById($id);
            $this->userRepository->delete($user);
            event(new DeletedContentEvent(USER_MODULE_SCREEN_NAME, $request, $user));

            return $response->setMessage(trans('core/acl::users.deleted'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage(trans('core/acl::users.cannot_delete'));
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postDeleteMany(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            if (Auth::user()->getKey() == $id) {
                return $response
                    ->setError()
                    ->setMessage(trans('core/acl::users.delete_user_logged_in'));
            }
            try {
                $user = $this->userRepository->findOrFail($id);
                $this->userRepository->delete($user);
                event(new DeletedContentEvent(USER_MODULE_SCREEN_NAME, $request, $user));
            } catch (Exception $ex) {
                return $response
                    ->setError()
                    ->setMessage(trans('core/acl::users.cannot_delete'));
            }
        }

        return $response->setMessage(trans('core/acl::users.deleted'));
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View| \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function getUserProfile($id, FormBuilder $formBuilder)
    {
        page_title()->setTitle(__('User profile # ') . $id);

        Assets::addScripts(['bootstrap-pwstrength', 'cropper'])
            ->addAppModule(['profile']);

        $user = $this->userRepository->findOrFail($id);

        $form = $formBuilder
            ->create(ProfileForm::class, ['model' => $user])
            ->setUrl(route('users.update-profile', $user->id));
        $password_form = $formBuilder
            ->create(PasswordForm::class)
            ->setUrl(route('users.change-password', $user->id));

        $can_change_profile = Auth::user()->getKey() == $id || Auth::user()->isSuperUser();

        if (!$can_change_profile) {
            $form->disableFields();
            $form->removeActionButtons();
            $password_form->disableFields();
            $password_form->removeActionButtons();
        }

        if (Auth::user()->isSuperUser()) {
            $password_form->remove('old_password');
        }
        $form = $form->renderForm();
        $password_form = $password_form->renderForm();

        return view('core.acl::users.profile.base', compact('user', 'form', 'password_form', 'can_change_profile'));
    }

    /**
     * @param int $id
     * @param UpdateProfileRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postUpdateProfile($id, UpdateProfileRequest $request, BaseHttpResponse $response)
    {
        $user = $this->userRepository->findById($id);

        /**
         * @var User $currentUser
         */
        $currentUser = Auth::user();
        if (($currentUser->hasPermission('users.update-profile') && $currentUser->getKey() === $user->id) ||
            $currentUser->isSuperUser()
        ) {
            if ($user->email !== $request->input('email')) {
                $users = $this->userRepository->getModel()
                    ->where('email', $request->input('email'))
                    ->where('id', '<>', $user->id)
                    ->count();
                if ($users) {
                    return $response
                        ->setError()
                        ->setMessage(trans('core/acl::users.email_exist'))
                        ->withInput();
                }
            }

            if ($user->username !== $request->input('username')) {
                $users = $this->userRepository->getModel()
                    ->where('username', $request->input('username'))
                    ->where('id', '<>', $user->id)
                    ->count();
                if ($users) {
                    return $response
                        ->setError()
                        ->setMessage(trans('core/acl::users.username_exist'))
                        ->withInput();
                }
            }
        }

        $user->fill($request->input());
        $user->completed_profile = 1;
        $this->userRepository->createOrUpdate($user);
        do_action(USER_ACTION_AFTER_UPDATE_PROFILE, USER_MODULE_SCREEN_NAME, $request, $user);

        return $response->setMessage(trans('core/acl::users.update_profile_success'));
    }

    /**
     * @param int $id
     * @param UpdatePasswordRequest $request
     * @param ChangePasswordService $service
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postChangePassword(
        $id,
        UpdatePasswordRequest $request,
        ChangePasswordService $service,
        BaseHttpResponse $response
    )
    {
        $request->merge(['id' => $id]);
        $result = $service->execute($request);

        if ($result instanceof Exception) {
            return $response
                ->setError()
                ->setMessage($result->getMessage());
        }

        return $response->setMessage(trans('core/acl::users.password_update_success'));
    }

    /**
     * @param ChangeProfileImageRequest $request
     * @param UpdateProfileImageService $service
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postModifyProfileImage(
        ChangeProfileImageRequest $request,
        UpdateProfileImageService $service,
        BaseHttpResponse $response
    )
    {
        try {
            $result = $service->execute($request);

            if ($result instanceof Exception) {
                return $response->setMessage($result->getMessage());
            }

            if (!empty($result)) {
                return $response
                    ->setData($result)
                    ->setMessage(trans('core/acl::users.update_avatar_success'));
            }

            return $response
                ->setError()
                ->setMessage(__('Error when changing profile image, please try again later.'));
        } catch (Exception $ex) {
            return $response
                ->setError()
                ->setMessage($ex->getMessage());
        }
    }

    /**
     * @param string $lang
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     * @throws Exception
     */
    public function getLanguage($lang)
    {
        if ($lang != false && array_key_exists($lang, Assets::getAdminLocales())) {
            if (Auth::check()) {
                UserMeta::setMeta('admin-locale', $lang);
                cache()->forget(md5('cache-dashboard-menu-' . Auth::user()->getKey()));
            }
            session()->put('admin-locale', $lang);
        }

        return redirect()->back();
    }

    /**
     * @param string $theme
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function getTheme($theme)
    {
        if (Auth::check()) {
            UserMeta::setMeta('admin-theme', $theme);
        }

        session()->put('admin-theme', $theme);

        try {
            return redirect()->back();
        } catch (Exception $exception) {
            return redirect()->route('access.login');
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function getImpersonate($id)
    {
        $user = $this->userRepository->findOrFail($id);
        Auth::user()->impersonate($user);

        return redirect()->route('dashboard.index');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function leaveImpersonation()
    {
        Auth::user()->leaveImpersonation();
        return redirect()->route('dashboard.index');
    }
}
