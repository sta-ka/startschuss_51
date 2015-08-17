<?php namespace Event\Participant;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model {

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
	public $timestamp = true;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
	protected $guarded = [];

}