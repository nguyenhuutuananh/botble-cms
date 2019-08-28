<?php

namespace Botble\ACL\Http\Requests;

use Auth;
use Botble\Support\Http\Requests\Request;

class UpdatePasswordRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author Sang Nguyen
     */
    public function rules()
    {
        $rules = [
            'password'              => 'required|min:6|max:60',
            'password_confirmation' => 'same:password',
        ];

        if (Auth::user()->isSuperUser()) {
            return $rules;
        }

        return ['old_password' => 'required|min:6|max:60'] + $rules;
    }
}
