<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Http\JsonResponse;

class SubscriptionRequest extends Request
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_date'     => 'required',
            'end_date'       => 'required',
            'company_id'     => 'required',
            'price'          => 'required'
        ];
    }

    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
