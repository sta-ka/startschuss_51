<?php namespace App\Http\Requests\Experience;

use App\Http\Requests\Request;

class UpdateExperienceRequest extends Request {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [
			'company' 			=> 'required',
			'industry' 			=> 'required',
			'job_description' 	=> 'required',
			'month_start' 		=> 'numeric|between:1,12',
			'year_start' 		=> 'numeric|min:1980|max:'.date('Y'),
			'month_end' 		=> 'numeric|between:0,12',
			'year_end'	 		=> 'numeric|min:0|max:'.date('Y'),
			'to_date'			=> 'in:0,1'
		];

		if ( (! \Input::get('month_end') || ! \Input::get('year_end')) && ! \Input::get('to_date')) {
			$rules['valid_end_date'] = 'required';
		} elseif (checkdate(\Input::get('month_start'), 1, \Input::get('year_start'))) {
			$start_date = \Date::germanToSql('01.' . \Input::get('month_start') .'.'. \Input::get('year_start'));

			$end_date = \Input::get('to_date') ? '2030-01-01' : \Date::germanToSql('01.'. \Input::get('month_end') .'.'. \Input::get('year_end'));

			if (strtotime($end_date) - strtotime($start_date) < 0) {
				$rules['valid_start_date'] = 'required';
			}		
		}

		return $rules;
	}

}
