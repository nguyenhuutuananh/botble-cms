<?php

namespace Botble\ACL\Services;

use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\Media\Services\UploadsManager;
use Botble\Support\Services\ProduceServiceInterface;
use Exception;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Storage;

class UpdateProfileImageService implements ProduceServiceInterface
{
    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * @var UploadsManager
     */
    protected $uploadsManager;

    /**
     * ResetPasswordService constructor.
     * @param UserInterface $userRepository
     * @param UploadsManager $uploadsManager
     */
    public function __construct(UserInterface $userRepository, UploadsManager $uploadsManager)
    {
        $this->userRepository = $userRepository;
        $this->uploadsManager = $uploadsManager;
    }

    /**
     * @param Request $request
     * @return bool|\Exception
     * @author Sang Nguyen
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function execute(Request $request)
    {
        if (!$request->hasFile('avatar_file')) {
            return new Exception(trans('core/acl::users.error_update_profile_image'));
        }

        $user = $this->userRepository->findById($request->input('user_id'));

        $file = $request->file('avatar_file');
        $fileName = $file->getClientOriginalName();
        $fileExtension = $file->getClientOriginalExtension();

        $avatar = [
            'path'     => config('core.acl.general.avatar.container_dir') . DIRECTORY_SEPARATOR . $user->username . '/full-' . Str::slug(basename($fileName, $fileExtension)) . '-' . time() . '.' . $fileExtension,
            'realPath' => config('core.acl.general.avatar.container_dir') . DIRECTORY_SEPARATOR . $user->username . '/thumb-' . Str::slug(basename($fileName, $fileExtension)) . '-' . time() . '.' . $fileExtension,
            'ext'      => $fileExtension,
            'mime'     => $request->file('avatar_file')->getMimeType(),
            'name'     => $fileName,
            'user'     => $user->id,
            'size'     => $request->file('avatar_file')->getSize(),
        ];

        config()->set('filesystems.default', 'local');
        config()->set('filesystems.disks.local.root', config('core.base.general.upload.base_dir'));

        File::deleteDirectory(config('core.base.general.upload.base_dir') . DIRECTORY_SEPARATOR . config('core.acl.general.avatar.container_dir') . DIRECTORY_SEPARATOR . $user->username);
        $this->uploadsManager->saveFile($avatar['path'], file_get_contents($request->file('avatar_file')->getRealPath()));

        $crop = new CropAvatar($request->input('avatar_src'), $request->input('avatar_data'), $avatar);
        $user->profile_image = $crop->getResult();
        $this->userRepository->createOrUpdate($user);

        return $crop->getResult();
    }
}
