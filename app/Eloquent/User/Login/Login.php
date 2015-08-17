<?php namespace App\Eloquent\User\Login;

use Illuminate\Database\Eloquent\Model;

class Login extends Model {

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
		return $this->hasOne('App\Eloquent\User\User', 'id', 'user_id');
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

}