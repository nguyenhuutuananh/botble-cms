<?php

namespace Botble\ACL\Forms;

use Botble\ACL\Http\Requests\CreateSuperUserRequest;
use Botble\Base\Forms\FormAbstract;

class SuperUserForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $this
            ->setModuleName(SUPER_USER_MODULE_SCREEN_NAME)
            ->setFormOption('class', 'form-xs')
            ->setFormOption('template', 'core.base::forms.form-modal')
            ->setValidatorClass(CreateSuperUserRequest::class)
            ->add('email', 'text', [
                'label'      => trans('core/acl::users.email'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => __('Ex: example@gmail.com'),
                    'data-counter' => 60,
                ],
            ])
            ->add('close', 'button', [
                'label' => trans('core/base::forms.cancel'),
                'attr'  => [
                    'class'               => 'btn btn-warning',
                    'data-fancybox-close' => true,
                ],
            ])
            ->add('submit', 'submit', [
                'label' => trans('core/base::forms.add'),
                'attr'  => [
                    'class' => 'btn btn-info float-right',
                ],
            ]);
    }
}