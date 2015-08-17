<?php namespace App\Eloquent\Applicant\Education;

use Illuminate\Database\Eloquent\Model;

use Date;

class Education extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
	public $table = 'education';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
	protected $guarded = [];

/*
|--------------------------------------------------------------------------
|       Accessors and Mutators
|--------------------------------------------------------------------------
*/

    /**
     * Get 'start_date' attribute in german format
     *
     * @param $value
     *
     * @return string
     */
    public function getStartDateAttribute($value)
	{
		return Date::sqlToGerman($value);
	}

    /**
     * Get 'end_date' attribute in german format
     *
     * @param $value
     *
     * @return string
     */
    public function getEndDateAttribute($value)
	{
		return Date::sqlToGerman($value);
	}


}