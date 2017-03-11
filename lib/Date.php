<?php
class Date extends DateTime
{
    private $isWorking;

    public static function createFromFormat($format, $value)
    {
        if ($value === null) {
            return new Date();
        }

        $dateTime = parent::createFromFormat($format, $value);
        $dateTimeStr = $dateTime->format($format);
        $dateParts = explode('-', $dateTimeStr);

        $date = new Date();
        
        if ($dateParts[2] === null)
            $dateParts[2] = 1;

        $date->setDate((int) $dateParts[0], (int) $dateParts[1], (int) $dateParts[2]);
        return $date;
    }

    public function isWorkingDay()
    {
        if ($this->isWorking !== null) {
            return $this->isWorking;
        }

        return in_array($this->format('D'), array('Sun', 'Sat')) 
            ? false
            : true;
    }

    public function setIsWorking($working)
    {
        $this->isWorking = $working;
    }
}