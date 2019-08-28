<?php

namespace Botble\Contact\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ContactReplyRequest extends Request
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
            'message' => 'required',
        ];
    }
}
