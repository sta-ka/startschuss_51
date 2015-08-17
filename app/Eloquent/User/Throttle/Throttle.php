<?php namespace App\Eloquent\User\Throttle;

use Illuminate\Database\Eloquent\Model;

use Date;

class Throttle extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'throttle';

/*
|--------------------------------------------------------------------------
|       Accessors and Mutators
|--------------------------------------------------------------------------
*/

    public function getLastAttemptAtAttribute($value)
    {
        if ( ! $value) {
            return '-';
        }

        return Date::format($value, 'datetime');
    }

}