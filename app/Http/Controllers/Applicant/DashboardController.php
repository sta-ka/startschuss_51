<?php namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;

/**
 * Class DashboardController
 *
 * @package App\Http\Controllers\Applicant
 */
class DashboardController extends Controller {

    /**
     * @return \Illuminate\View\View
     */
	public function index()
	{
		return view('applicant.dashboard.show');
	}

}