<?php

class Calendar
{
    private $date;
    private $dateRange;
    private $json;
    private $path;
    private $currentDirectory;

    public function __construct()
    {
        $this->date = new Date();
    }

    public function createJSONIfNeeded()
    {
        $this->createFolderIfNeeded();
        $this->path = $this->currentDirectory . $this->date->format('F') . '-' . $this->date->format('Y') . '.json';

        if (file_exists($this->path) && file_get_contents($this->path) !== '') {
            $this->loadJSON();
            return;
        }

        $months = array();

        foreach ($this->daterange as $date) {
            $months[] = array(
                'dayFormattedNumber' => $date->format('jS'),
                'dayString' => $date->format('l'),
                'date' => $date->format('Y-m-d'),
                'isWorking' => $date->isWorkingDay());
        }

        $this->json = json_encode($months, JSON_PRETTY_PRINT);
        $this->outputJSON();
    }

    private function createFolderIfNeeded()
    {
        $this->currentDirectory = 'data/calendar/' . $this->date->format('Y') . '/';

        if (!file_exists($this->currentDirectory)) {
            mkdir($this->currentDirectory, 0775, true);
        }
    }

    public function outputJSON()
    {
        $fp = fopen($this->path, "w+");
        file_put_contents($this->path, $this->json);
        fclose($fp);
    }

    public function getDateValue($date)
    {
        $_array = json_decode($this->json, true);

        foreach ($_array as &$calDate) {
            if ($calDate['date'] == $date) {
                return $calDate;
            }
        }
        return null;
    }

    public function setIsWorking($date, $bool)
    {
        $_array = json_decode($this->json, true);

        foreach ($_array as &$calDate) {
            if ($calDate['date'] == $date) {
                $calDate['isWorking'] = (bool) $bool;
                break;
            }
        }

        $this->json = json_encode($_array, JSON_PRETTY_PRINT);
    }

    public function loadJSON()
    {
        $this->createFolderIfNeeded();
        $this->path = $this->currentDirectory . $this->date->format('F') . '-' . $this->date->format('Y') . '.json';
        $this->json = file_get_contents($this->path);
    }

    public function generateDateRange()
    {
        $begin = new Date($this->date->format('Y-M-01'));
        $end = $this->date->modify('last day of this month');

        $interval = new DateInterval('P1D');
        $this->daterange = new DatePeriod($begin, $interval, $end);
    }

    private function getWorkingDays()
    {
        $workingDays = array();

        $_array = json_decode($this->json, true);

        foreach ($_array as &$calDate) {
            if ($calDate['isWorking']) 
                $workingDays[] = $calDate;
        }
      
        return $workingDays;
    }

    public function getWorkingDaysCount()
    {
        return count($this->getWorkingDays());
    }

    public function getJSON()
    {
        return $this->json;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }
}