<?php namespace App\Eloquent\Misc\Article;

use Illuminate\Database\Eloquent\Model;

class Article extends Model {

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
|       Scopes
|--------------------------------------------------------------------------
*/

	public function scopeActive($query) {
		return $query->where('active', 1);
	}

	public function scopeLatest($query) {
		return $query->orderBy('created_at', 'desc');
	}

}