<?php

namespace App\Http\Controllers\Schedule;

use App\Contracts\Auth\AuthContract;
use App\Contracts\Category\CategoryContracts;
use App\Contracts\Clinic\ClinicContract;
use App\Contracts\Doctor\DoctorContract;
use App\Contracts\Patient\PatientContract;
use App\Contracts\Schedule\ScheduleContract;
use App\Http\Controllers\BaseController;
use App\Models\DoctorsAvailabilities;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleController extends BaseController
{
    private $AuthContract;
    private $ClinicContract;
    private $DoctorContract;
    private $ScheduleContract;
    public function __construct(AuthContract $AuthContract, ClinicContract $ClinicContract, DoctorContract $DoctorContract, ScheduleContract $ScheduleContract)
    {
        $this->AuthContract = $AuthContract;
        $this->ClinicContract = $ClinicContract;
        $this->DoctorContract = $DoctorContract;
        $this->ScheduleContract = $ScheduleContract;
    }
    public function index(Request $request)
    {
        $this->setPageTitle('Schedule List');
        $fetchClinicListData = [];
        $fetchDoctorId = '';
        if (auth()->user()->roles->first()->slug == 'doctor') {
            $fetchDoctorId = $this->DoctorContract->findId(auth()->user()->id);
            $fetchClinicList = $this->ScheduleContract->findDoctorToClinicId($fetchDoctorId->id);
            $fetchData = $fetchClinicList;
            $fetchDoctorId = $fetchDoctorId;
        } else {
            $fetchData = $this->ScheduleContract->getAllClinic();
        }
        // dd($fetchData);
        return view('admin.schedule-management.index', compact('fetchData', 'fetchDoctorId'));
    }

    public function doctorScheduleList($uuid)
    {
        $this->setPageTitle('Clinic Doctor List');
        $isUser = $this->ClinicContract->findId($uuid);
        $schedules = $isUser->schedules;
        return view('admin.doctor.doctor', compact('schedules', 'uuid'));
    }

    public function edit($uuid)
    {
        $this->setPageTitle('Edit Schedule');
        $data = $this->ScheduleContract->findId($uuid);
        return view('admin.schedule-management.add-edit', compact('data'));
    }
    public function details($uuid)
    {
        $this->setPageTitle('Schedule Details');
        $fetchData = $this->ScheduleContract->findId($uuid);
        return view('admin.schedule-management.details', compact('fetchData'));
    }
    // *************************************************************************************
    public function add(Request $request)
    {
        $this->setPageTitle('Schedule');
        if ($request->isMethod('post')) {
            DB::beginTransaction();
            try {
                $insertArry = $request->except(['_token', '_method', 'id']);
                $isSchedule = $this->ScheduleContract->createSchedule($insertArry);
                $availableDay = $this->ScheduleContract->createAvailableDay($insertArry, $isSchedule);
                // dd($availableDay);
                $breakTime = $this->ScheduleContract->createBreakTime($insertArry, $availableDay);
                $message = 'Schedule Created Successfully';
                if ($isSchedule) {
                    DB::commit();
                    return $this->responseRedirect('admin.schedule.list', $message, 'success', false);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                logger($e->getMessage() . '--' . $e->getFile() . '--' . $e->getLine());
                return $this->responseRedirectBack('Something went wrong', 'error', true);
            }
        }
        return view('admin.schedule-management.add-edit');
    }
    // ******************
    // public function getBookingTimes(Request $request)
    // {
    //     $person = null;
    //     $date = $request->input('date');
    //     $carbonDate = Carbon::createFromFormat('Y-m-d', $date);
    //     $dayOfWeek = $carbonDate->format('l');
    //     $insertArry = $request->except(['_token', '_method']);
    //     $fetchData = $this->ScheduleContract->featchSchedule($insertArry);
    //     //  $fetchData->slots;
    //     //  $fetchData->scheduleBreakTime;

    //     // Initialize an array to hold processed slots
    //     $processedSlots = [];
    //     foreach ($fetchData->slots as $key => $time) {
    //         if ($time->available_from != null && $time->available_day == $dayOfWeek) {
    //             // Perform calculations for each slot
    //             $processedSlot = $this->processSlot($time, $dayOfWeek, $date, $person);

    //             // Add processed slot to the array
    //             $processedSlots[] = $processedSlot;
    //         }
    //     }

    //     $breakTimeSlots = [];
    //     foreach ($fetchData->scheduleBreakTime as $key => $breakTime) {
    //         if ($breakTime->break_from != null && $breakTime->break_day == $dayOfWeek) {
    //             // Perform calculations for each slot
    //             // dd($breakTime->break_from);
    //             $breakTimeSlots = $this->breakTimeSlots($breakTime, $dayOfWeek, $date, $person);

    //             // Add processed slot to the array
    //             $breakTimeSlots[] = $breakTimeSlots;
    //         }
    //         // dd($breakTime);
    //     }

    //     // dd($breakTimeSlots);
    //     return view('admin.schedule-management.partials.booking_times', compact('fetchData', 'dayOfWeek', 'date', 'processedSlots', 'breakTimeSlots'));
    // }

    // private function processSlot($time, $dayOfWeek, $date, $person)
    // {
    //     // Perform necessary calculations for each slot
    //     // Example calculations:
    //     // dd($time);
    //     $person = $time->total_patient;
    //     $carbonStartTime = Carbon::parse($time->available_from);
    //     $carbonEndTime = Carbon::parse($time->available_to);
    //     $formattedStartTime = $carbonStartTime->format('H:i');
    //     $formattedEndTime = $carbonEndTime->format('H:i');
    //     $timeDifferenceInMinutes = $carbonEndTime->diffInMinutes($carbonStartTime);
    //     $totalTime = $timeDifferenceInMinutes;
    //     $dividedResult = $totalTime / $time->total_patient;
    //     $interval = $dividedResult . ' minutes';
    //     $rac = (10 / 100) * $time['person'];
    //     $emergency = (5 / 100) * $time['person'];
    //     // Example of other calculations...

    //     // Prepare processed slot data
    //     $processedSlot = [
    //         'start_time' => $formattedStartTime,
    //         'end_time' => $formattedEndTime,
    //         'interval' => $interval,
    //         'person' => $person,
    //         'rac' => $rac,
    //         'emergency' => $emergency
    //         // Add other processed data as needed
    //     ];
    //     return $processedSlot;
    // }

    // private function breakTimeSlots($breakTime, $dayOfWeek, $date, $person)
    // {
    //     $formattedStartTime = \Carbon\Carbon::parse($breakTime->break_from)->format('h:i A');
    //     $formattedEndTime = \Carbon\Carbon::parse($breakTime->break_to)->format('h:i A');
    //     $breakTimeSlots = [
    //         'break_from' => $formattedStartTime,
    //         'break_to' => $formattedEndTime,
    //         'schedule_id' => $breakTime->schedule_id,
    //         'doctors_availabilitie_id' => $breakTime->doctors_availabilitie_id
    //     ];
    //     // dd($breakTimeSlots);
    //     return $breakTimeSlots;
    // }

    // ****************************************************************************************

    public function getBookingTimes(Request $request)
    {
        $date = $request->input('date');
        $carbonDate = Carbon::createFromFormat('Y-m-d', $date);
        $dayOfWeek = $carbonDate->format('l');
        $insertArray = $request->except(['_token', '_method']);
        $fetchData = $this->ScheduleContract->featchSchedule($insertArray);

        $processedSlots = $this->processSlots($fetchData->slots, $dayOfWeek, $date);
        $breakTimeSlots = $this->processBreakTimes($fetchData->scheduleBreakTime, $dayOfWeek);

        // dd($breakTimeSlots);
        return view('admin.schedule-management.partials.booking_times', compact('fetchData', 'dayOfWeek', 'date', 'processedSlots', 'breakTimeSlots'));
    }

    private function processSlots($slots, $dayOfWeek, $date)
    {
        $processedSlots = [];
        foreach ($slots as $time) {
            if ($time->available_from !== null && $time->available_day === $dayOfWeek) {
                $processedSlots[] = $this->processSlot($time, $dayOfWeek, $date);
            }
        }
        return $processedSlots;
    }

    private function processSlot($time, $dayOfWeek, $date)
    {
        $carbonStartTime = Carbon::parse($time->available_from);
        $carbonEndTime = Carbon::parse($time->available_to);
        $totalTimeInMinutes = $carbonEndTime->diffInMinutes($carbonStartTime);
        $interval = $totalTimeInMinutes / $time->total_patient;
        $rac = (10 / 100) * $time->total_patient;
        $emergency = (5 / 100) * $time->total_patient;

        return [
            'start_time' => $carbonStartTime->format('H:i'),
            'end_time' => $carbonEndTime->format('H:i'),
            'interval' => $interval . ' minutes',
            'person' => $time->total_patient,
            'rac' => $rac,
            'emergency' => $emergency,
        ];
    }

    private function processBreakTimes($breakTimes, $dayOfWeek)
    {
        $processedBreakTimes = [];
        foreach ($breakTimes as $breakTime) {
            if ($breakTime->break_from !== null && $breakTime->break_day === $dayOfWeek) {
                $processedBreakTimes[] = $this->processBreakTime($breakTime);
            }
        }
        return $processedBreakTimes;
    }

    private function processBreakTime($breakTime)
    {
        return [
            'break_from' => Carbon::parse($breakTime->break_from)->format('h:i A'),
            'break_to' => Carbon::parse($breakTime->break_to)->format('h:i A'),
            'schedule_id' => $breakTime->schedule_id,
            'doctors_availabilitie_id' => $breakTime->doctors_availabilitie_id,
        ];
    }

    // ****************************************************************************************
    public function doctorScheduleSlot($did, $cid)
    {
        $fetchSchedule = Schedule::where('id', $did)->first();
        // $fetchSchedule->doctorBreakTime;
        $fetchSchedule->slots;
        // dd($fetchSchedule->toArray());
        return view('admin.schedule-management.schedule-list', compact('fetchSchedule', 'cid'));
    }
}


// public function getBookingTimes(Request $request)
// {
//     // Retrieve date from the request
//     $date = $request->input('date');

//     // Convert date to Carbon instance
//     $carbonDate = Carbon::createFromFormat('Y-m-d', $date);

//     // Get the day of the week from the date
//     $dayOfWeek = $carbonDate->format('l');

//     // Exclude unnecessary inputs
//     $insertArry = $request->except(['_token', '_method']);

//     // Fetch schedule data using the ScheduleContract
//     $fetchData = $this->ScheduleContract->featchSchedule($insertArry);

//     // Initialize an array to hold processed slots
//     $processedSlots = [];

//     foreach ($fetchData->slots as $key => $time) {
//         if ($time->available_from != null && $time->available_day == $dayOfWeek) {
//             // Perform calculations for each slot
//             $processedSlot = $this->processSlot($time, $dayOfWeek, $date);

//             // Add processed slot to the array
//             $processedSlots[] = $processedSlot;
//         }
//     }

//     // Pass processed data to the view and return the view
//     return view('admin.schedule-management.partials.booking_times', compact('processedSlots', 'date'));
// }

// private function processSlot($time, $dayOfWeek, $date)
// {
//     // Perform necessary calculations for each slot
//     // Example calculations:

//     $carbonStartTime = Carbon::parse($time->available_from);
//     $carbonEndTime = Carbon::parse($time->available_to);
//     $formattedStartTime = $carbonStartTime->format('H:i');
//     $formattedEndTime = $carbonEndTime->format('H:i');
//     $timeDifferenceInMinutes = $carbonEndTime->diffInMinutes($carbonStartTime);
//     $totalTime = $timeDifferenceInMinutes;
//     $dividedResult = $totalTime / $time->total_patient;
//     $interval = $dividedResult . ' minutes';

//     // Example of other calculations...
    
//     // Prepare processed slot data
//     $processedSlot = [
//         'start_time' => $formattedStartTime,
//         'end_time' => $formattedEndTime,
//         'interval' => $interval,
//         // Add other processed data as needed
//     ];

//     return $processedSlot;
// }
