<?php

class WorkingDays
{
    private $content;
    private $header;
    private $calendar;
    private $currentMonth;
    private $subHeader;
    private $monthPicker;
    private $currentDate;
    private $paramDate;

    public function __construct()
    {
        $this->calendar = new Calendar();
        $this->calendar->generateDateRange();

        switch ($_GET['action']) {
            case 'specificdate':
                $this->LoadPopup();
            break;
            case 'dayoff':
                $this->ApplyWorkingDayChanges();
            break;
            default:
                $this->paramDate = Date::createFromFormat('Y-m', $_GET['date']);
                $this->LoadCalendar();
            break;
        }
    }

    private function ApplyWorkingDayChanges()
    {
        $this->paramDate = Date::createFromFormat('Y-m-d', $_GET['date']);
        $this->calendar->setDate($this->paramDate);
        $this->calendar->loadJSON();
        $calDate = $this->calendar->getDateValue($this->paramDate->format('Y-m-d'));
        $this->calendar->setIsWorking($this->paramDate->format('Y-m-d'), !$calDate['isWorking']);
        $this->calendar->outputJSON();

        header('Location: /?action=default&date=' . $this->paramDate->format('Y-m'));
    }

    private function LoadCalendar()
    {
        $this->calendar->createJSONIfNeeded();
        $array = json_decode($this->calendar->getJSON(), true);

        $this->header = 'Working days';
        $this->currentMonth = $this->calendar->getDate()->format('F');
        $this->subHeader = $this->calendar->getWorkingDaysCount() . ' days';

        $this->monthPicker = '
            <form id="month-picker" class="form-inline" method="get" id="dashboard-filter">
                <div class="form-group">
                    <input type="text" name="date" class="date-picker form-control" value="' . $this->paramDate->format('Y-m') . '">
                    <button type="submit" class="btn btn-default" value="submit" aria-label="Left Align">Submit</button>
                </div>
            </form>
        ';

        $this->content = '<div id="calendar"><table>';
        foreach($array as $i=>$calDate) {
            if ($i % 5 == 0) {
                $this->content .= '<tr>';
            }

            $this->content .= '
                <td id="' . ($calDate['isWorking'] ? 'working' : 'free') . '">
                    <a href="?action=specificdate&date=' . $calDate['date'] . '">
                        <span class="date">' . $calDate['dayString'] . 
                        ' ' . $calDate['dayFormattedNumber'] . '</span>
                    </a>
                </td>';
        }

        $this->content .= '</table></div>';
        $this->ReloadCalendar($_GET['date']);
    }

    private function ReloadCalendar($date)
    {
        if ($this->currentDate !== null || $date === $this->currentDate)
            return;
       
        $this->paramDate = Date::createFromFormat('Y-m', $_GET['date']);
        $this->calendar->setDate($this->paramDate);
        $this->calendar->generateDateRange();
        $this->currentDate = $date;
        $this->LoadCalendar();
    }

    private function LoadPopup()
    {
        $this->paramDate = Date::createFromFormat('Y-m-d', $_GET['date']);
        $this->calendar->setDate($this->paramDate);
        $this->calendar->loadJSON();
        $this->header = 'Managing date';

        $this->currentMonth = $this->paramDate->format('F');
        $this->subHeader = $this->paramDate->format('l') . ' ' . $this->paramDate->format('jS');
        $calDate = $this->calendar->getDateValue($this->paramDate->format('Y-m-d'));

        $takedayoff = '<button type="button" class="btn btn-default" aria-label="Left Align">
                        <span class="glyphicon glyphicon-plane" aria-hidden="true"></span> Take the day off
                        </button>';

        $removedayoff = '<button type="button" class="btn btn-default" aria-label="Left Align">
                        <span class="glyphicon glyphicon-lock" aria-hidden="true"></span> Remove the day off
                        </button>';

        $this->content .= '
            <div id="dayoff-button">
                <a href="?action=dayoff&date=' . $this->paramDate->format('Y-m-d') . '">'
                  . ($calDate['isWorking'] ? $takedayoff : $removedayoff) . '
                </a>
            </div>';
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getCurrentMonth()
    {
        return $this->currentMonth;
    }

    public function getMonthPicker()
    {
        return $this->monthPicker;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getSubHeader()
    {
        return $this->subHeader;
    }
}