<?php

namespace App\Http\Requests\Investment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InvestmentRequest extends FormRequest
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
			"amount" => "required",
			"note" => "required",
			"login_id" => "required|integer",
		];
	}
	/**
	 * Handle a failed validation attempt.
	 *
	 * @param  \Illuminate\Contracts\Validation\Validator  $validator
	 *
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function failedValidation(Validator $validator)
	{
		throw new HttpResponseException(response()->json(["status"=>"NG","message"=>$validator->errors()], 422));
	}
}