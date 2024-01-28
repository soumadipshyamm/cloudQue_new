<?php

namespace App\Services\Schedule;

use App\Contracts\Schedule\ScheduleContract;
use App\Models\DoctorBreakTime;
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
    function findAvalableSlot($id)
    {
        return DoctorsAvailabilities::where('schedule_id', $id)->where('is_active', 1)->get();
    }
    function featchSchedule(array $data)
    {
        $data = Schedule::with('slots', 'scheduleBreakTime')->where(['doctor_id' => $data['doctorId'], 'clinics_id' => $data['clinicId']])->where('is_active', 1)->first();
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
            $schedules->scheduleBreakTime()->delete();
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
                    'schedule_id' => $id->id,
                    'available_day' => $day,
                    'available_from' => $timeSlot,
                    'available_to' => $scheduleData['available_to'][$day][$index],
                    'total_patient' => $scheduleData['available_person'][$day][$index] ?? null,
                ];
            }
        }
        $isCreate = DoctorsAvailabilities::insert($data);
        if ($isCreate) {
            $avaldoctorTime = DoctorsAvailabilities::where('schedule_id', $id->id)->get();
            return $avaldoctorTime;
        }
    }
    function createBreakTime(array $datas, $ids)
    {
        $scheduleId = $ids->first()->schedule_id;

        // Get schedule data
        $scheduleData = $datas;
        
        // Initialize an array to store doctor availability time IDs
        $docAvlTimeIds = [];
        
        foreach ($ids as $id) {
            if ($id->available_from != null && $id->available_to != null) {
                $docAvlTimeIds[] = $id->id;
            }
        }
        
        // Initialize an array to store the data for DoctorBreakTime records
        $data = [];
        
        foreach ($scheduleData['break_from'] as $day => $timeSlots) {
            foreach ($timeSlots as $index => $timeSlot) {
                // Check if the time slot is not empty and not null
                if (!empty($timeSlot) && $timeSlot != null) {
                    $data[] = [
                        'schedule_id' => $scheduleId,
                        'doctors_availabilitie_id' => $docAvlTimeIds[$index], // Corrected column name
                        'break_day' => $day,
                        'break_from' => $timeSlot,
                        'break_to' => $scheduleData['break_to'][$day][$index],
                    ];
                }
            }
        }
        
        // Insert data into the DoctorBreakTime table
        $isCreate = DoctorBreakTime::insert($data);
        
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
    function findDoctorToClinicId($id)
    {
        return Schedule::with('clinices')->where('doctor_id', $id)->get();
    }
    // function findClinic($id)
    // {
    //     return Schedule::where('clinics_id', $id)->get();
    // }
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
