<?php
namespace Bolt\Extension\Rixbeck\Gapps;

class When extends \When
{

    public function getFequency()
    {
        return $this->frequency;
    }

    public function getStartDate()
    {
        return $this->start_date;
    }

    public function getTryDate()
    {
        return $this->try_date;
    }

    public function getEndDate()
    {
        return $this->end_date;
    }

    public function getByMonth()
    {
        return $this->bymonth;
    }

    public function getGoByMonth()
    {
        return $this->gobymonth;
    }

    public function getGoByWeekNo()
    {
        return $this->gobyweekno;
    }

    public function getByWeekNo()
    {
        return $this->byweekno;
    }

    public function getGoByYearDay()
    {
        return $this->gobyyearday;
    }

    public function getByYearDay()
    {
        return $this->byyearday;
    }

    public function getGoByMonthDay()
    {
        return $this->gobymonthday;
    }

    public function getByMonthDay()
    {
        return $this->bymonthday;
    }

    public function getGoByDay()
    {
        return $this->gobyday;
    }

    public function getByDay()
    {
        return $this->byday;
    }

    public function getGoBySetPos()
    {
        return $this->gobysetpos;
    }

    public function getBySetPos()
    {
        return $this->bysetpos;
    }

    public function getSuggestions()
    {
        return $this->suggestions;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function getCounter()
    {
        return $this->counter;
    }

    public function getGoEndDate()
    {
        return $this->goenddate;
    }

    public function getInterval()
    {
        return $this->interval;
    }

    public function getWkst()
    {
        return $this->wkst;
    }

    public function getValidWeekDays()
    {
        return $this->valid_week_days;
    }

    public function valid_frequency()
    {
        return $this->valid_frequency;
    }

    public function keep_first_month_day()
    {
        return $this->keep_first_month_day;
    }
}