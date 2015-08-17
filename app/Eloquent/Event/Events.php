<?php namespace App\Eloquent\Event;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

use Date;

class Events extends Model {

	use SoftDeletes, RevisionableTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
	public $table = 'events';

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

	public function user()
	{
		return $this->belongsTo('App\Eloquent\User\User');
	}

	public function applications()
	{
		return $this->hasMany('App\Eloquent\Applicant\Application\Application', 'event_id');
	}

	public function requestedBy()
	{
		return $this->belongsTo('App\Eloquent\User\User', 'requested_by', 'id');
	}

	public function organizer()
	{
		return $this->belongsTo('App\Eloquent\Organizer\Organizer');
	}

	public function region()
	{
		return $this->belongsTo('App\Eloquent\Misc\Region\Region');
	}

	public function participants()
	{
		return $this->belongsToMany('App\Eloquent\Company\Company', 'participants', 'event_id', 'company_id')
					->withPivot('interview')
					->withPivot('comment')
					->withTimestamps();
	}

/*
|--------------------------------------------------------------------------
|       Scopes
|--------------------------------------------------------------------------
*/

	public function scopeUpcoming($query, $upcoming = true)
	{
		if ($upcoming == false) {
            return $query->where('end_date', '<=', new \DateTime('yesterday'));
        }

		return $query->where('end_date', '>', new \DateTime('yesterday'));
	}

	public function scopeVisible($query)
	{
		return $query->where('visible', 1);
	}

/*
|--------------------------------------------------------------------------
|       Accessors and Mutators
|--------------------------------------------------------------------------
*/

    /**
     * Get 'start_date' attribute in german format
     *
     * @param $value
     *
     * @return string
     */
    public function getStartDateAttribute($value)
	{
		return Date::sqlToGerman($value);
	}

    /**
     * Set 'start_date' attribute in SQL format
     *
     * @param $value
     *
     * @return string
     */
    public function setStartDateAttribute($value)
	{
		$this->attributes['start_date'] = Date::germanToSql($value);
	}

    /**
     * Get 'end_date' attribute in german format
     *
     * @param $value
     *
     * @return string
     */
    public function getEndDateAttribute($value)
	{
		return Date::sqlToGerman($value);
	}
	
    /**
     * Set 'end_date' attribute in SQL format
     *
     * @param $value
     *
     * @return string
     */
    public function setEndDateAttribute($value)
	{
		$this->attributes['end_date'] = Date::germanToSql($value);
	}

}