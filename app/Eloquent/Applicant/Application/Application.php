<?php namespace App\Eloquent\Applicant\Application;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model {

	use SoftDeletes;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
	protected $guarded = [];
/*
|--------------------------------------------------------------------------
|       Relationships
|--------------------------------------------------------------------------
*/

	public function user()
	{
		return $this->belongsTo('App\Eloquent\User\User', 'applicant_id');
	}

	public function applicant()
	{
		return $this->belongsTo('App\Eloquent\Applicant\Applicant', 'applicant_id', 'user_id');
	}

	public function company()
	{
		return $this->belongsTo('App\Eloquent\Company\Company');
	}

	public function event()
	{
		return $this->belongsTo('App\Eloquent\Event\Events');
	}


/*
|--------------------------------------------------------------------------
|       Accessors and Mutators
|--------------------------------------------------------------------------
*/

    /**
     * Get 'created_at' attribute in german format
     *
     * @param $value
     *
     * @return string
     */
    public function getCreatedAtAttribute($value)
	{
		return \Date::sqlToGerman($value);
	}

}