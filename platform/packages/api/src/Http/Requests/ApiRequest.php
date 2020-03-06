<?php

namespace Botble\Api\Http\Requests;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

/**
 * @mixin \Illuminate\Http\Concerns\InteractsWithInput
 */
abstract class ApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return BaseHttpResponse|void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = [];

        foreach ($validator->errors()->toArray() as $key => $message) {
            $errors[] = [
                'field' => $key,
                'error' => Arr::first($message),
            ];
        }

        $response = (new BaseHttpResponse)
            ->setError(true)
            ->setMessage(__('The given data is invalid'))
            ->setData($errors)
            ->setCode(422);

        throw new ValidationException($validator, $response);
    }
}
