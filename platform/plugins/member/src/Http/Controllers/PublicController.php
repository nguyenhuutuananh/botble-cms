<?php

namespace Botble\Member\Http\Controllers;

use Assets;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Media\Http\Requests\MediaFileRequest;
use Botble\Member\Http\Requests\AvatarRequest;
use Botble\Member\Http\Requests\SettingRequest;
use Botble\Member\Http\Requests\UpdatePasswordRequest;
use Botble\Member\Repositories\Interfaces\MemberActivityLogInterface;
use Botble\Member\Repositories\Interfaces\MemberInterface;
use Botble\Member\Services\CropAvatar;
use Exception;
use File;
use Hash;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use RvMedia;
use SeoHelper;
use Storage;
use Validator;

class PublicController extends Controller
{
    /**
     * @var MemberInterface
     */
    protected $memberRepository;

    /**
     * @var MemberActivityLogInterface
     */
    protected $activityLogRepository;

    /**
     * PublicController constructor.
     * @param Repository $config
     * @param MemberInterface $memberRepository
     * @param MemberActivityLogInterface $memberActivityLogRepository
     */
    public function __construct(
        Repository $config,
        MemberInterface $memberRepository,
        MemberActivityLogInterface $memberActivityLogRepository
    ) {
        $this->memberRepository = $memberRepository;
        $this->activityLogRepository = $memberActivityLogRepository;

        Assets::setConfig($config->get('plugins.member.assets'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getDashboard()
    {
        $user = auth()->guard('member')->user();

        SeoHelper::setTitle(auth()->guard('member')->user()->getFullName());

        return view('plugins.member::dashboard.index', compact('user'));
    }

    /**
     * @return \Response
     * @author Sang Nguyen
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getSettings()
    {
        SeoHelper::setTitle(__('Account settings'));

        $user = auth()->guard('member')->user();

        return view('plugins.member::settings.index', compact('user'));
    }

    /**
     * @param SettingRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postSettings(SettingRequest $request, BaseHttpResponse $response)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $day = $request->input('day');

        if ($year && $month && $day) {
            $request->merge(['dob' => implode('-', [$year, $month, $day])]);

            $validator = Validator::make($request->input(), [
                'dob' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return redirect()->route('public.member.settings');
            }
        }

        $this->memberRepository->createOrUpdate($request->input(), ['id' => auth()->guard('member')->user()->getKey()]);

        $this->activityLogRepository->createOrUpdate(['action' => 'update_setting']);

        return $response
            ->setNextUrl(route('public.member.settings'))
            ->setMessage(__('Update profile successfully!'));
    }

    /**
     * @return \Response
     * @author Sang Nguyen
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getSecurity()
    {
        SeoHelper::setTitle(__('Security'));

        return view('plugins.member::settings.security');
    }

    /**
     * @param UpdatePasswordRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postSecurity(UpdatePasswordRequest $request, BaseHttpResponse $response)
    {
        if (!Hash::check($request->input('current_password'), auth()->guard('member')->user()->getAuthPassword())) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/member::dashboard.current_password_not_valid'));
        }

        $this->memberRepository->update(['id' => auth()->guard('member')->user()->getKey()], [
            'password' => bcrypt($request->input('password')),
        ]);

        $this->activityLogRepository->createOrUpdate(['action' => 'update_security']);

        return $response->setMessage(trans('plugins/member::dashboard.password_update_success'));
    }

    /**
     * @param AvatarRequest $request
     * @return array
     * @author Sang Nguyen <sangnguyenplus@gmail.com>
     */
    public function postAvatar(AvatarRequest $request)
    {
        try {
            $member = auth()->guard('member')->user();

            $file = $request->file('avatar_file');
            $fileName = $file->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();

            $avatar = [
                'path'     => config('plugins.member.general.avatar.folder.container_dir') . DIRECTORY_SEPARATOR . md5($member->email) . '/full-' . Str::slug(basename($fileName,
                        $fileExtension)) . '-' . time() . '.' . $fileExtension,
                'realPath' => config('plugins.member.general.avatar.folder.container_dir') . DIRECTORY_SEPARATOR . md5($member->email) . '/thumb-' . Str::slug(basename($fileName,
                        $fileExtension)) . '-' . time() . '.' . $fileExtension,
                'ext'      => $fileExtension,
                'mime'     => $file->getMimeType(),
                'name'     => $fileName,
                'user'     => $member->id,
                'size'     => $file->getSize(),

            ];
            File::deleteDirectory(config('plugins.member.general.avatar.folder.upload') . DIRECTORY_SEPARATOR . config('plugins.member.general.avatar.folder.container_dir') . DIRECTORY_SEPARATOR . md5($member->email));

            config()->set('filesystems.disks.local.root', config('plugins.member.general.avatar.folder.upload'));

            Storage::put($avatar['path'], file_get_contents($file->getRealPath()), 'public');
            $crop = new CropAvatar($request->input('avatar_src'), $request->input('avatar_data'), $avatar);
            $member->avatar = str_replace(public_path(), '',
                    config('plugins.member.general.avatar.folder.upload')) . '/' . $crop->getResult();

            $this->memberRepository->createOrUpdate($member);

            $this->activityLogRepository->createOrUpdate([
                'action' => 'changed_avatar',
            ]);

            return [
                'error'   => false,
                'message' => __('plugins/member::dashboard.update_avatar_success'),
                'result'  => $member->avatar,
            ];
        } catch (Exception $ex) {
            return [
                'error'   => true,
                'message' => $ex->getMessage(),
            ];
        }
    }

    /**
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getActivityLogs(BaseHttpResponse $response)
    {
        $activities = $this->activityLogRepository->getAllLogs(auth()->guard('member')->user()->getKey());

        foreach ($activities->items() as &$activity) {
            $activity->description = $activity->getDescription();
        }

        return $response->setData($activities);
    }

    /**
     * @param MediaFileRequest $request
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function postUpload(MediaFileRequest $request)
    {
        $result = RvMedia::handleUpload($request->file('upload'), 0, 'members');

        if ($result['error'] == false) {
            $file = $result['data'];
            return response('<script>parent.setImageValue("' . url($file->url) . '"); </script>')->header('Content-Type',
                'text/html');
        }

        return response('<script>alert("' . Arr::get($result, 'message') . '")</script>')->header('Content-Type',
            'text/html');
    }
}
