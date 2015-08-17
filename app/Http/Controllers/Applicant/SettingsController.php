<?php namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\BaseSettingsController;

/**
 * Class SettingsController
 *
 * @package App\Http\Controllers\Applicant
 */
class SettingsController  extends BaseSettingsController {

    /**
     * Type of user
     *
     * @var string
     */
    protected $user_type = 'applicant';

}