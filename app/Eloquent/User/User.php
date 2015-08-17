<?php namespace App\Eloquent\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

use Illuminate\Auth\Authenticatable;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements UserContract {

	use Authenticatable, SoftDeletes, RevisionableTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
	protected $hidden = ['password'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
	protected $guarded = ['password'];

	protected $dontKeepRevisionOf = [
	    'activated_at',
	    'activation_code',
	    'reset_password_code'
	];

/*
|--------------------------------------------------------------------------
|       Model Events
|--------------------------------------------------------------------------
*/

    public static function boot()
    {
        parent::boot();

        static::creating(function()
        {
            \Notification::error('user_created_unsuccessful');
        });

        static::created(function()
        {
            \Notification::success('user_created_successful');
        });

    }
/*
|--------------------------------------------------------------------------
|       Relationships
|--------------------------------------------------------------------------
*/

	public function applications()
	{
		return $this->hasMany('App\Eloquent\Applicant\Application\Application', 'applicant_id');
	}

	public function events()
	{
		return $this->hasMany('App\Eloquent\Event\Events', 'user_id');
	}
	
	public function company()
	{
		return $this->belongsToMany('App\Eloquent\Company\Company', 'company_users', 'user_id', 'company_id');
	}

	public function logins()
	{
		return $this->hasMany('App\Eloquent\User\Login\Login', 'user_id');
	}	

	public function group()
	{
		return $this->belongsToMany('App\Eloquent\User\Group\Group', 'users_groups', 'user_id', 'group_id');
	}

	public function throttle()
	{
		return $this->hasOne('App\Eloquent\User\Throttle\Throttle', 'user_id');
	}

	public function applicant()
	{
		return $this->hasOne('App\Eloquent\Applicant\Applicant', 'user_id');
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
        return \Date::format($value, 'datetime');
    }

    /**
     * Get 'last_login' attribute
     *
     * @param $value
     *
     * @return string
     */
    public function getLastLoginAttribute($value)
    {
        if ( ! $value ) {
            return '-';
        }

        return \Date::format($value, 'datetime');
    }


}