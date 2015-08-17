<?php namespace App\Eloquent\Company\Job;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model {

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
| Model Events
|--------------------------------------------------------------------------
*/

    public static function boot()
    {
        parent::boot();

        static::updating(function()
        {
            \Notification::error('job_update_unsuccessful');
        });

        static::updated(function()
        {
            \Notification::success('job_update_successful');
        });

    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

	public function company()
	{
		return $this->belongsTo('App\Eloquent\Company\Company');
	}

/*
|--------------------------------------------------------------------------
| Scopes
|--------------------------------------------------------------------------
*/

	public function scopeActive($query)
	{
		return $query->where('active', 1);
	}

	public function scopeApproved($query, $approved = true)
	{
		return $query->where('approved', $approved);
	}

/*
|--------------------------------------------------------------------------
| Mutators
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