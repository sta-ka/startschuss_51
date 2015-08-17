<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider {

	/**
	* Register the service provider.
	*/
	public function register()
	{
		$this->app->bind('App\Eloquent\Applicant\ApplicantRepositoryInterface', 	 			'App\Eloquent\Applicant\DbApplicantRepository');
		$this->app->bind('App\Eloquent\Applicant\Application\ApplicationRepositoryInterface', 	'App\Eloquent\Applicant\Application\DbApplicationRepository');
		$this->app->bind('App\Eloquent\Company\CompanyRepositoryInterface',						'App\Eloquent\Company\DbCompanyRepository');
		$this->app->bind('App\Eloquent\Company\Job\JobRepositoryInterface', 		 			'App\Eloquent\Company\Job\DbJobRepository');
		$this->app->bind('App\Eloquent\Event\Participant\ParticipantRepositoryInterface', 		'App\Eloquent\Event\Participant\DbParticipantRepository');
		$this->app->bind('App\Eloquent\Event\EventRepositoryInterface',							'App\Eloquent\Event\DbEventRepository');
		$this->app->bind('App\Eloquent\Applicant\Education\EducationRepositoryInterface',		'App\Eloquent\Applicant\Education\DbEducationRepository');
		$this->app->bind('App\Eloquent\Applicant\Experience\ExperienceRepositoryInterface',		'App\Eloquent\Applicant\Experience\DbExperienceRepository');
		$this->app->bind('App\Eloquent\Organizer\OrganizerRepositoryInterface',					'App\Eloquent\Organizer\DbOrganizerRepository');
		$this->app->bind('App\Eloquent\Misc\Log\InfoRepositoryInterface',						'App\Eloquent\Misc\Log\DbInfoRepository');
		$this->app->bind('App\Eloquent\Misc\Article\ArticleRepositoryInterface',				'App\Eloquent\Misc\Article\DbArticleRepository');
		$this->app->bind('App\Eloquent\Misc\Audience\AudienceRepositoryInterface',				'App\Eloquent\Misc\Audience\DbAudienceRepository');
		$this->app->bind('App\Eloquent\Misc\City\CityRepositoryInterface',						'App\Eloquent\Misc\City\DbCityRepository');
		$this->app->bind('App\Eloquent\Misc\Region\RegionRepositoryInterface',					'App\Eloquent\Misc\Region\DbRegionRepository');
		$this->app->bind('App\Eloquent\User\UserRepositoryInterface',							'App\Eloquent\User\DbUserRepository');
		$this->app->bind('App\Eloquent\User\Login\LoginRepositoryInterface', 		 			'App\Eloquent\User\Login\DbLoginRepository');
	}

}