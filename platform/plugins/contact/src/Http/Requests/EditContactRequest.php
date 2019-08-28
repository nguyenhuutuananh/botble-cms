<?php

namespace Botble\Contact\Http\Requests;

use Botble\Contact\Enums\ContactStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class EditContactRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author Sang Nguyen
     */
    public function rules()
    {
        return [
            'status' => Rule::in(ContactStatusEnum::values()),
        ];
    }
}
