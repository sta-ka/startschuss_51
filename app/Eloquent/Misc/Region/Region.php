<?php namespace App\Eloquent\Misc\Region;

use Illuminate\Database\Eloquent\Model;

class Region extends Model {

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

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

	public function events()
	{
		return $this->hasMany('App\Eloquent\Event\Events');
	}
}