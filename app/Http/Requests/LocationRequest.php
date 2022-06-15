<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ErrorResource;

class LocationRequest extends FormRequest
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
     * return the response if the validation fails.
     *
     * @return array
     */

    protected function errorResponse()
    {
        $errors = [
            "statusCode" => 400,
            "message" => "Validation fails",
            "error" => $this->validator->errors()->messages(),
            "data" => null
        ];
        return new ErrorResource($errors);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'searchTerm' => 'required|string',
            'offset' => 'required|integer'
        ];
    }
}
