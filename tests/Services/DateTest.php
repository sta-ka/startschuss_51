<?php

use App\Services\Date\DateService;

class DateTest extends TestCase {

    protected $date;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        $this->date = new DateService();
    }

    /**
     * Test format date method
     *
     * @dataProvider formatDateProvider
     */
    public function testFormatDate($actual, $format, $expected)
    {
        $this->assertEquals($expected, $this->date->format($actual, $format));
    }

    public function formatDateProvider()
    {
        return array(
            # 0
            array('2012-04-3', 'date', '03.04.2012'),
            # 1
            array('2012-04-3', 'day', '3'),
            # 2
            array('2012-04-3', 'ISO', '2012-04-03'),
            # 3
            array('2012-04-3 22:14:12', 'datetime', '03.04.2012 22:14:12'),
            # 4
            array('2012-04-3 22:14:12', 'time', '22:14:12'),
            # 5
            array('2012-04-3 22:14:12', 'year', '2012'),
            # 6
            array('2012-04-3 22:14:12', 'datetime_short', '03.04.2012 - 22:14'),
            # 7
            array('2012-04-3 22:14:12', 'datetime', '03.04.2012 22:14:12')
        );
    }

    /**
     * Test output date/dates in a human friendly format
     *
     * @dataProvider outputMonthDateDateProvider
     */
    public function testOutputMonthDate($start_date, $end_date, $long, $expected)
    {
        $this->assertEquals($expected, $this->date->monthDate($start_date, $end_date, $long));
    }

    public function outputMonthDateDateProvider()
    {
        // start_date, end_date, long, expected
        return array(
            # 0
            array('3.4.2012', '7.4.2012', true,  '3. - 7. April'),
            # 1
            array('4.3.2012', false, true,  '4. März'),
            # 2
            array('4.3.2012', false, false,  '4. März'),
            # 3
            array('03.07.2013', '03.07.2013', false, '3. Juli'),
            # 4
            array('27.08.2013', '03.09.2013', false, '27. - 3. Sept')
        );
    }

    /**
     * Test output date in german format
     *
     * @dataProvider outputGermanDateProvider
     */
    public function testOutputGermanDate($actual, $long, $expected)
    {
        $this->assertEquals($expected, $this->date->german($actual, $long));
    }

    public function outputGermanDateProvider()
    {
        return array(
            # 0
            array('3.4.2012', true, '3. April 2012'),
            # 1
            array('04.08.2014', false, '4. Aug 2014'),
            # 2
            array('04.08.2014', true, '4. August 2014'),
            # 3
            array('4.7.2012', false, '4. Juli 2012'),
            # 4
            array('07.11.2013', false, '7. Nov 2013'),
            # 5
            array('07.11.2013', true, '7. November 2013')
        );
    }

}
