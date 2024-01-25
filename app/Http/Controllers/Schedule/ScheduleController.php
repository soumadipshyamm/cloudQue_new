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
        $fetchData = $this->ScheduleContract->getAllClinic();
        return view('admin.schedule-management.index', compact('fetchData'));
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
                // $breakTime = $this->ScheduleContract->createBreakTime($insertArry,$availableDay);
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
    public function getBookingTimes(Request $request)
    {
        // dd($request->all());
        $selectedDate = $request->input('date');
        $carbonDate = Carbon::createFromFormat('Y-m-d', $selectedDate);
        $dayOfWeek = $carbonDate->format('l');
        $insertArry = $request->except(['_token', '_method']);
        $fetchData = $this->ScheduleContract->featchSchedule($insertArry);

        return view('admin.schedule-management.partials.booking_times', compact('fetchData','dayOfWeek'));
    }

    // ****************************************************************************************
    public function doctorScheduleSlot($did, $cid)
    {
        $fetchSchedule = Schedule::where('id', $did)->first();
        $fetchSchedule->slots;
        // dd($fetchSchedule->slots->toArray());
        return view('admin.schedule-management.schedule-list', compact('fetchSchedule','cid'));
    }
}
// "date" => "2024-02-02"
//   "doctorId" => "3"
//   "clinicId" => "1"
