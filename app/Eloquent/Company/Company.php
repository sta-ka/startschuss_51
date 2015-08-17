<?php namespace App\Eloquent\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Company extends Model {

	use SoftDeletes, RevisionableTrait;

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
|       Relationships
|--------------------------------------------------------------------------
*/

	public function users()
	{
		return $this->belongsToMany('App\Eloquent\User\User', 'company_users', 'company_id', 'user_id');
	}

	public function applications()
	{
		return $this->hasMany('App\Eloquent\Applicant\Application\Application', 'company_id');
	}

	public function events()
	{
		return $this->belongsToMany('App\Eloquent\Event\Events', 'participants', 'company_id', 'event_id');
	}

	public function jobs()
	{
		return $this->hasMany('App\Eloquent\Company\Job\Job', 'company_id');
	}

	public function participants()
	{
		return $this->belongsToMany('App\Eloquent\Event\Events', 'participants', 'company_id', 'event_id')
					->withPivot('interview')
					->withPivot('comment')
					->withTimestamps();
	}

}