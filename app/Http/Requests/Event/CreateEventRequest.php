<?php namespace App\Http\Requests\Event;

use App\Http\Requests\Request;

use Carbon\Carbon;

class CreateEventRequest extends Request {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name'			=> 'required|min:3',
			'slug'			=> 'required|min:3|alpha_dash',
			'location'		=> 'required|min:3',
			'start_date'	=> 'required|date|date_format:"d.m.Y"|after:'.Carbon::now()->format('d.m.Y'),
			'end_date'		=> 'sometimes|date|date_format:"d.m.Y"|after:start_date',
			'organizer_id'	=> 'required'
		];
	}

}
