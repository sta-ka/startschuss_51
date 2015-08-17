<?php namespace App\Http\Requests\Organizer;

use App\Http\Requests\Request;

class UploadLogoRequest extends Request {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'logo' => 'required|image|max:1000',
		];
	}

}
