<?php

namespace App\Entities;

use App\Models\UserModel;
use Carbon\Carbon;
use CodeIgniter\Entity\Entity;

class FolhaPontoEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function getUser()
    {
        if (isset($this->attributes['user_id'])) {
            $userModel = new UserModel();
            return $userModel->find($this->attributes['user_id']);
        }

        return null;
    }
    public function getHoursOfDay()
    {
        // Convert time values to Carbon instances for proper calculation
        $breakExit = Carbon::parse($this->break_exit);

        $breakEntry = Carbon::parse($this->break_entry);
        $exitHour = Carbon::parse($this->exit_hour);
        $entryHour = Carbon::parse($this->entry_hour);

        // Calculate break duration in minutes
        $breakDuration = $breakExit->diffInMinutes($breakEntry);

        // Calculate working hours in minutes
        $workingHours = $exitHour->diffInMinutes($entryHour);

        // Subtract break duration from working hours
        $netWorkingHours = $workingHours - $breakDuration;

        // Convert net working hours back to hours and minutes
        $hours = floor($netWorkingHours / 60);
        $minutes = $netWorkingHours % 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }
    public function getOverTime()
    {
        $total = Carbon::parse($this->getHoursOfDay());
        if ($total->greaterThan(Carbon::parse('8:00'))) {
            $overTime = $total->subHour(8);
            //dd($overTime);
            return $overTime->format('H:i');
        }else return '00:00';


    }

    public function isBusinessDay()
    {
        $dateForCheck = Carbon::create($this->entry_date) ;
        $hollidays = config('config\HollidaysOfYear')->getHollidays();
        if($dateForCheck->isWeekend())
        {
            return 'danger';
        }else if(in_array($dateForCheck->format('Y-m-d'),$hollidays))
        {
            return  'warning';
        }
        return '';
    }
}
