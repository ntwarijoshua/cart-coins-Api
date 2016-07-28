<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Http\JsonResponse;

class CompanyRequest extends Request
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
            'name'      => 'required',
            'vat'       => 'required|unique:companies',
            'phone'     => 'required',
            'manager_id'=> 'required',

        ];
    }


    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
