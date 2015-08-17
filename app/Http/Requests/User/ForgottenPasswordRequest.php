<?php namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class ForgottenPasswordRequest extends Request {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'username'	=> 'required',
			'email' 	=> 'required|email'
		];
	}

}
