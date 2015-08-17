<?php namespace App\Services\Date;


/**
 * Class DateService
 *
 * @package App\Services\Date
 */
class DateService {

    /**
     * @var array
     */
    protected $formats = [
				'datetime' 		 => '%d.%m.%Y %H:%M:%S',
				'datetime_short' => '%d.%m.%Y - %H:%M',
				'date' 			 => '%d.%m.%Y',
				'time' 			 => '%H:%M:%S',
				'day' 			 => '%d',
				'year' 			 => '%Y',
				'ISO' 			 => '%Y-%m-%d'
			];

    /**
     * @var array
     */
    protected $months_fullname = [
				1	=> "Januar",
				2	=> "Februar",
				3	=> "März",
				4	=> "April",
				5	=> "Mai",
				6	=> "Juni",
				7	=> "Juli",
				8	=> "August",
				9	=> "September",
				10	=> "Oktober",
				11	=> "November",
				12	=> "Dezember"
			];

    /**
     * @var array
     */
    protected $months_shortname = [
				1	=> "Jan",
				2	=> "Feb",
				3	=> "März",
				4	=> "Apr",
				5	=> "Mai",
				6	=> "Juni",
				7	=> "Juli",
				8	=> "Aug",
				9	=> "Sept",
				10	=> "Okt",
				11	=> "Nov",
				12	=> "Dez"
			];

    /**
     * Format date according to given string
     *
     * @param $date
     * @param $str
     *
     * @return bool|string
     */
    public function format($date, $str)
	{
		if (! in_array($str, array_keys($this->formats))) {
            return false;
        }

        $str = $this->formats[$str]; // convert alias to string

        $time = strtotime($date);

        if ($str == '%d') {
            return ltrim(strftime($str, $time), '0');
        }

        return strftime($str, $time);
	}


    /**
     * Return date/date span in a a human friendly format
     *
     * @param $start_date
     * @param $end_date
     * @param bool $long
     *
     * @return bool|string
     */
    public function monthDate($start_date, $end_date = false, $long = true)
	{
		$months = $this->getMonthNames($long);

		$start = explode('.', $start_date);

        $start['day']   = ltrim($start[0], '0');
        $start['month'] = $months[ltrim($start[1], '0')];

		if ($start_date == $end_date || $end_date === false) {
			return sprintf('%d. %s', $start['day'], $start['month']); // returns 31. Januar / 31. Jan
		} elseif ($start_date != $end_date && $end_date == true) {
			$end = explode('.', $end_date);

			$end['day']   = ltrim($end[0],'0');
            $end['month'] = $months[ltrim($end[1],'0')];

			return sprintf('%d. - %d. %s', $start['day'], $end['day'], $end['month']); // returns 12. - 14. Januar / 12. - 14. Jan
		}

		return false;
	}

	/**
	 * Return date containing month and year
     *
     * @param $date
     * @param bool $long
     *
     * @return string
     */
	public function monthYearDate($date, $long = true)
	{
		$months = $this->getMonthNames($long);

		$date = explode('.', $date);
		
		$month = $months[ltrim($date[1],'0')];
		$year  = $date[2];

		return sprintf('%s %d', $month, $year); // returns Januar 2014 / Jan 2014
	}


    /**
     * Return date containing day and name of month
     *
     * @param $date
     * @param bool $long
     *
     * @return string
     */
    public function german($date, $long = true)
	{
		$months = $this->getMonthNames($long);

		$date = explode('.', $date);
		
		$day   = ltrim($date[0],'0');
		$month = $months[ltrim($date[1],'0')];
		$year  = $date[2];

		return sprintf('%d. %s %d', $day, $month, $year); // returns 12. Januar 2014 / 12. Jan 2014
	}


    /**
     * Returns an array of dates between a start and end-date in german format
     *
     * @param $start_date
     * @param $end_date
     *
     * @return array
     */
    public function diff($start_date, $end_date)
	{
		$start = strtotime($start_date);
		$end   = strtotime($end_date);

		$difference = abs(($end - $start) / (60*60*24)); // difference between end and start date in days
		$dates = [];
		
		for ($i = 0; $i <= $difference; $i++) {
			$date = $start + $i * (60*60*24);
			$dates[date('d.m.Y' , $date)] = date('d.m.Y' , $date); 
		}

		return $dates;
	}


    /**
     * Convert date: 20.10.2014 -> 2014-10-20
     *
     * @param $date
     *
     * @return string
     */
    public function germanToSql($date)
	{
		$d = explode('.', $date);
		
		return sprintf('%04d-%02d-%02d', $d[2], $d[1], $d[0]);
	}

	/**
	 * Convert date: 2014-10-20 -> 20.10.2014
     *
     * @param $date
     *
     * @return string
     */
	public function sqlToGerman($date) 
	{
		$d = explode('-', $date);
		
		return sprintf('%02d.%02d.%04d', $d[2], $d[1], $d[0]);
	}


    /**
     * Get short/long version of month names
     *
     * @param bool $long
     *
     * @return array
     */
    private function getMonthNames($long)
	{
		if ($long === true) {
			return $this->months_fullname;
		}

		return $this->months_shortname;
	}

}