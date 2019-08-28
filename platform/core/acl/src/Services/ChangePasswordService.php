<?php

namespace Botble\ACL\Services;

use Auth;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\Support\Services\ProduceServiceInterface;
use Exception;
use Hash;
use Illuminate\Http\Request;

class ChangePasswordService implements ProduceServiceInterface
{
    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * ResetPasswordService constructor.
     * @param UserInterface $userRepository
     */
    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return bool|\Exception
     * @author Sang Nguyen
     */
    public function execute(Request $request)
    {
        $currentUser = Auth::user();

        if (!$currentUser->isSuperUser()) {
            if (!Hash::check($request->input('old_password'), Auth::user()->getAuthPassword())) {
                return new Exception(trans('core/acl::users.current_password_not_valid'));
            }
        }

        $user = $this->userRepository->findById($request->input('id', Auth::user()->getKey()));
        $this->userRepository->update(['id' => $user->id], [
            'password' => Hash::make($request->input('password')),
        ]);

        Auth::logoutOtherDevices($request->input('password'));

        do_action(USER_ACTION_AFTER_UPDATE_PASSWORD, USER_MODULE_SCREEN_NAME, $request, $user);

        return $user;
    }
}
