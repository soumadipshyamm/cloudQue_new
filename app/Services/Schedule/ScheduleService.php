<?php

namespace App\Services\Schedule;

use App\Contracts\Schedule\ScheduleContract;
use App\Models\DoctorsAvailabilities;
use App\Models\Profile;
use App\Models\role;
use App\Models\Schedule;
use App\Models\ScheduleTime;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Exists;

class ScheduleService implements ScheduleContract
{
    function findId($id)
    {
        return Schedule::find($id);
    }
    function getAll()
    {
        return Schedule::where('is_active', 1)->get();
    }
    function getAllClinic()
    {
        return Schedule::where('is_active', 1)->get();
    }
    function featchSchedule(array $data)
    {
        $data = Schedule::with('slots')->where(['doctor_id' => $data['doctorId'], 'clinics_id' => $data['clinicId']])->where('is_active', 1)->first();
        return $data;
    }

    function updateSchedule(array $data)
    {
        $clinicData = auth()->user()->clinicUser->first()->id ?? null;
        $isCreate = Schedule::where('id', $data['id'])->update([
            'profile_clinics_id' => $data['clinicId'] ?? $clinicData,
            'user_id' => $data['doctorId'],
            'valid_date' => $data['valid_date'] ?? null,
            'schedule' => $data['schedule'] ?? null
        ]);
        return $isCreate;
    }

    function createSchedule(array $data)
    {
        $scheduleData = $data;
        $schedules = Schedule::where('clinics_id', $scheduleData['clinicId'])
            ->where('doctor_id', $scheduleData['doctorId'])
            ->first();

        // If schedules exist, delete them and their associated time slots
        if ($schedules) {
            $schedules->slots()->delete();
            $schedules->delete();
        }

        // Create a new schedule
        $createdSchedule = Schedule::create([
            'clinics_id' => $scheduleData['clinicId'] ?? null,
            'doctor_id' => $scheduleData['doctorId'],
            'valid_date' => $scheduleData['valid_date'] ?? null,
            'schedule' => $scheduleData['schedule'] ?? null,
        ]);

        return $createdSchedule;
    }

    function createAvailableDay(array $datas, $id)
    {
        $scheduleData = $datas;

        $data = [];

        foreach ($scheduleData['available_from'] as $day => $timeSlots) {
            foreach ($timeSlots as $index => $timeSlot) {
                $data[] = [
                    // 'clinics_id' => $scheduleData['clinicId'],
                    // 'doctor_id' => $scheduleData['doctorId'],
                    'schedule_id' => $id->id,
                    'available_day' => $day,
                    'available_from' => $timeSlot,
                    'available_to' => $scheduleData['available_to'][$day][$index],
                    'total_patient' => $scheduleData['available_person'][$day][$index] ?? null,
                ];
            }
        }

        $isCreate = DoctorsAvailabilities::insert($data);
        return $isCreate;
    }

    // function createSchedule(array $data)
    // {
    //     $clinicData = auth()->user()->clinicUser->first()->id ?? null;
    //     $isCreate = Schedule::create([
    //         'profile_clinics_id' => $data['clinicId'] ?? $clinicData,
    //         'user_id' => $data['doctorId'],
    //         'valid_date' => $data['valid_date'] ?? null,
    //         'schedule' => $data['schedule'] ?? null,
    //     ]);
    //     return $isCreate;
    // }


    function createBreakTime(array $datas, $id)
    {
        dd($datas);
    }


    function schdule($data)
    {
        $timeArr = [];
        foreach ($data as $time) {
            $timeArr = $time;
        }
        return $data;
    }
    function time($data)
    {
        $timeArr = [];
        foreach ($data as $time) {
            $timeArr = $time['times'];
        }
    }
    // **********************************************************************
    function findClinicIdToDoctor($id)
    {
        return Schedule::where('profile_clinics_id', $id)->get();
    }
    function scheduleList($id)
    {
        return Schedule::where('user_id', $id)->first();
    }
}


// 'available_from'=>, 'available_to'=>, 'total_patient'=>,
// 'available_from'=>, 'available_to'=>, 'total_patient'=>,
// 'available_from'=>, 'available_to'=>, 'total_patient'=>,
// 'available_from'=>, 'available_to'=>,  'total_patient'=>,
// 'available_from'=>, 'available_to'=>, 'total_patient'=>,
// 'available_from'=>, 'available_to'=>, 'total_patient'=>,
// 'available_from'=>, 'available_to'=>,  'total_patient'=>,
