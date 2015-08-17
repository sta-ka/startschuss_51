<?php namespace App\Http\Requests\Event;

use App\Http\Requests\Request;

class UpdateGeneralDataRequest extends Request {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name'       => 'required|min:3',
			'location'   => 'required|min:3',
			'start_date' => 'required|date|date_format:"d.m.Y"', // after rule omitted (editing old events)
			'end_date'	 => 'sometimes|date|date_format:"d.m.Y"|after:start_date',
			'interviews' => 'in:0,1'
		];
	}

}
