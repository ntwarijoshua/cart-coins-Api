<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Http\JsonResponse;

class RewardRequest extends Request
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
            'company_id'        => 'required',
            'reward_title'      => 'required|unique:rewards',
            'reward_details'    => 'required',
            'points'            => 'required',

        ];
    }

    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
