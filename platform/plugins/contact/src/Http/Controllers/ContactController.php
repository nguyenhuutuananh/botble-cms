<?php

namespace Botble\Contact\Http\Controllers;

use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\EmailHandler;
use Botble\Contact\Enums\ContactStatusEnum;
use Botble\Contact\Forms\ContactForm;
use Botble\Contact\Http\Requests\ContactReplyRequest;
use Botble\Contact\Http\Requests\EditContactRequest;
use Botble\Contact\Repositories\Interfaces\ContactReplyInterface;
use Botble\Contact\Tables\ContactTable;
use Botble\Contact\Repositories\Interfaces\ContactInterface;
use Botble\Setting\Supports\SettingStore;
use Exception;
use Illuminate\Http\Request;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;

class ContactController extends BaseController
{

    /**
     * @var ContactInterface
     */
    protected $contactRepository;

    /**
     * @param ContactInterface $contactRepository
     * @author Sang Nguyen
     */
    public function __construct(ContactInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * @param ContactTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function getList(ContactTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/contact::contact.menu'));

        return $dataTable->renderTable();
    }

    /**
     * @param $id
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getEdit($id, FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/contact::contact.edit'));

        $contact = $this->contactRepository->findOrFail($id);

        return $formBuilder->create(ContactForm::class, ['model' => $contact])->renderForm();
    }

    /**
     * @param $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, EditContactRequest $request, BaseHttpResponse $response)
    {
        $contact = $this->contactRepository->findOrFail($id);

        $contact->fill($request->input());

        $this->contactRepository->createOrUpdate($contact);

        event(new UpdatedContentEvent(CONTACT_MODULE_SCREEN_NAME, $request, $contact));

        return $response
            ->setPreviousUrl(route('contacts.list'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function getDelete($id, Request $request, BaseHttpResponse $response)
    {
        try {
            $contact = $this->contactRepository->findById($id);
            $this->contactRepository->delete($contact);
            event(new DeletedContentEvent(CONTACT_MODULE_SCREEN_NAME, $request, $contact));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.cannot_delete'));
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @author Sang Nguyen
     * @throws Exception
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
            $contact = $this->contactRepository->findOrFail($id);
            $this->contactRepository->delete($contact);
            event(new DeletedContentEvent(CONTACT_MODULE_SCREEN_NAME, $request, $contact));
        }

        return $response
            ->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param ContactReplyRequest $request
     * @param $id
     * @param SettingStore $setting
     * @param EmailHandler $emailHandler
     * @throws \Throwable
     */
    public function postReply(
        ContactReplyRequest $request,
        $id,
        EmailHandler $emailHandler,
        BaseHttpResponse $response,
        ContactReplyInterface $contactReplyRepository
    ) {
        $contact = $this->contactRepository->findOrFail($id);

        $emailHandler->send($request->input('message'), 'Re: ' . $contact->subject, $contact->email);

        $contactReplyRepository->create([
            'message'    => $request->input('message'),
            'contact_id' => $id,
        ]);

        $contact->status = ContactStatusEnum::READ();
        $this->contactRepository->createOrUpdate($contact);

        return $response
            ->setMessage(__('Message sent successfully!'));
    }
}
