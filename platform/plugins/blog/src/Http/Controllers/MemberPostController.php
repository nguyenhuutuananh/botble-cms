<?php

namespace Botble\Blog\Http\Controllers;

use Assets;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Blog\Models\Post;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Blog\Services\StoreCategoryService;
use Botble\Blog\Services\StoreTagService;
use Botble\Blog\Forms\MemberPostForm;
use Botble\Blog\Http\Requests\MemberPostRequest;
use Botble\Member\Models\Member;
use Botble\Member\Repositories\Interfaces\MemberActivityLogInterface;
use Botble\Member\Repositories\Interfaces\MemberInterface;
use Botble\Blog\Tables\MemberPostTable;
use Exception;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RvMedia;
use SeoHelper;

class MemberPostController extends Controller
{
    /**
     * @var MemberInterface
     */
    protected $memberRepository;

    /**
     * @var PostInterface
     */
    protected $postRepository;

    /**
     * @var MemberActivityLogInterface
     */
    protected $activityLogRepository;

    /**
     * PublicController constructor.
     * @param Repository $config
     * @param MemberInterface $memberRepository
     * @param PostInterface $postRepository
     * @param MemberActivityLogInterface $memberActivityLogRepository
     */
    public function __construct(
        Repository $config,
        MemberInterface $memberRepository,
        PostInterface $postRepository,
        MemberActivityLogInterface $memberActivityLogRepository
    ) {
        $this->memberRepository = $memberRepository;
        $this->postRepository = $postRepository;
        $this->activityLogRepository = $memberActivityLogRepository;

        Assets::setConfig($config->get('plugins.member.assets'));
    }

    /**
     * @param Request $request
     * @param MemberPostTable $postTable
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View|\Response
     * @throws \Throwable
     * @author Sang Nguyen
     */
    public function getList(MemberPostTable $postTable)
    {
        SeoHelper::setTitle(__('Posts'));

        return $postTable->render('plugins.member::table.base');
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @throws \Throwable
     */
    public function getCreate(FormBuilder $formBuilder)
    {
        SeoHelper::setTitle(__('Write a post'));

        return $formBuilder->create(MemberPostForm::class)->renderForm();
    }

    /**
     * @param MemberPostRequest $request
     * @param StoreTagService $tagService
     * @param StoreCategoryService $categoryService
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function postCreate(
        MemberPostRequest $request,
        StoreTagService $tagService,
        StoreCategoryService $categoryService,
        BaseHttpResponse $response
    ) {

        if ($request->hasFile('image_input')) {
            $result = RvMedia::handleUpload($request->file('image_input'), 0, 'members');
            if ($result['error'] == false) {
                $file = $result['data'];
                $request->merge(['image' => $file->url]);
            }
        }

        /**
         * @var Post $post
         */
        $post = $this->postRepository->createOrUpdate(array_merge($request->except('input'), [
            'author_id'   => auth()->guard('member')->user()->getKey(),
            'author_type' => Member::class,
            'status'      => BaseStatusEnum::PENDING,
        ]));

        event(new CreatedContentEvent(POST_MODULE_SCREEN_NAME, $request, $post));

        $this->activityLogRepository->createOrUpdate([
            'action'         => 'create_post',
            'reference_name' => $post->name,
            'reference_url'  => route('public.member.posts.edit', $post->id),
        ]);

        $tagService->execute($request, $post);

        $categoryService->execute($request, $post);

        return $response
            ->setPreviousUrl(route('public.member.posts.list'))
            ->setNextUrl(route('public.member.posts.edit', $post->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @return string
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getEdit($id, FormBuilder $formBuilder, Request $request)
    {
        $post = $this->postRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth()->guard('member')->user()->getKey(),
            'author_type' => Member::class,
        ]);

        if (!$post) {
            abort(404);
        }

        event(new BeforeEditContentEvent(POST_MODULE_SCREEN_NAME, $request, $post));

        SeoHelper::setTitle(trans('plugins/blog::posts.edit') . ' "' . $post->name . '"');

        return $formBuilder
            ->create(MemberPostForm::class, ['model' => $post])
            ->renderForm();
    }

    /**
     * @param int $id
     * @param MemberPostRequest $request
     * @param StoreTagService $tagService
     * @param StoreCategoryService $categoryService
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function postEdit(
        $id,
        MemberPostRequest $request,
        StoreTagService $tagService,
        StoreCategoryService $categoryService,
        BaseHttpResponse $response
    ) {
        $post = $this->postRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth()->guard('member')->user()->getKey(),
            'author_type' => Member::class,
        ]);

        if (!$post) {
            abort(404);
        }

        if ($request->hasFile('image_input')) {
            $result = RvMedia::handleUpload($request->file('image_input'), 0, 'members');
            if ($result['error'] == false) {
                $file = $result['data'];
                $request->merge(['image' => $file->url]);
            }
        }

        $post->fill($request->except('input'));

        $this->postRepository->createOrUpdate($post);

        event(new UpdatedContentEvent(POST_MODULE_SCREEN_NAME, $request, $post));

        $this->activityLogRepository->createOrUpdate([
            'action'         => 'update_post',
            'reference_name' => $post->name,
            'reference_url'  => route('public.member.posts.edit', $post->id),
        ]);

        $tagService->execute($request, $post);

        $categoryService->execute($request, $post);

        return $response
            ->setPreviousUrl(route('public.member.posts.list'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function delete($id, BaseHttpResponse $response)
    {
        $post = $this->postRepository->getFirstBy([
            'id'          => $id,
            'author_id'   => auth()->guard('member')->user()->getKey(),
            'author_type' => Member::class,
        ]);

        if (!$post) {
            abort(404);
        }

        $this->postRepository->delete($post);

        $this->activityLogRepository->createOrUpdate([
            'action'         => 'delete_post',
            'reference_name' => $post->name,
        ]);

        return $response->setMessage(__('Delete post successfully!'));
    }
}
