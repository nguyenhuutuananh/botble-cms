<?php

namespace Botble\ACL\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ChangeProfileImageRequest extends Request
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
            'avatar_file' => 'required|image|mimes:jpg,jpeg,png',
        ];
    }
}
