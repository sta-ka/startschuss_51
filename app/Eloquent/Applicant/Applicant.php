<?php namespace App\Eloquent\Applicant;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model {

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
	protected $guarded = [];

/*
|--------------------------------------------------------------------------
|       Model Events
|--------------------------------------------------------------------------
*/

    public static function boot()
    {
        parent::boot();

        static::updating(function()
        {
            \Notification::error('profile_update_unsuccessful');
        });

        static::updated(function()
        {
            \Notification::success('profile_update_successful');
        });

    }

/*
|--------------------------------------------------------------------------
| 		Relationships
|--------------------------------------------------------------------------
*/

	public function user()
	{
		return $this->belongsTo('App\Eloquent\User\User', 'user_id');
	}

	public function educations()
	{
		return $this->hasMany('App\Eloquent\Applicant\Education\Education', 'applicant_id')->orderBy('end_date', 'desc');
	}

	public function experiences()
	{
		return $this->hasMany('App\Eloquent\Applicant\Experience\Experience', 'applicant_id')->orderBy('end_date', 'desc');
	}

/*
|--------------------------------------------------------------------------
|       Accessors and Mutators
|--------------------------------------------------------------------------
*/

    /**
     * Get 'created_at' attribute
     *
     * @param $value
     *
     * @return string
     */
    public function getCreatedAtAttribute($value)
	{
		return \Date::sqlToGerman($value);
	}

    /**
     * Get 'birthday' attribute
     *
     * @param $value
     *
     * @return string
     */
    public function getBirthdayAttribute($value)
    {
        if (! $value) {
            return false;
        }

        return \Date::sqlToGerman($value);
    }

    /**
     * Set 'birthday' attribute
     *
     * @param $value
     *
     * @return string
     */
    public function setBirthdayAttribute($value)
    {
        if (! $value) {
            return false;
        }

        $this->attributes['birthday'] = \Date::germanToSql($value);
    }

}