<?php namespace App\Http\Requests\Applicant;

use App\Http\Requests\Request;

class UpdateContactsRequest extends Request {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'email'	=> 'email|max:40',
			'phone'	=> 'min:5|max:20'
		];
	}

}
