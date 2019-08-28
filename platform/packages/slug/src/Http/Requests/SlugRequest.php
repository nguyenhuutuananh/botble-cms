<?php

namespace Botble\Slug\Http\Requests;

use Botble\Support\Http\Requests\Request;

class SlugRequest extends Request
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
            'name'    => 'required',
            'slug_id' => 'required',
        ];
    }
}
