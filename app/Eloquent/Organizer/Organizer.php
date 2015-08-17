<?php namespace App\Eloquent\Organizer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Organizer extends Model {
	
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

	public function events()
	{
		return $this->hasMany('App\Eloquent\Event\Events');
	}

}