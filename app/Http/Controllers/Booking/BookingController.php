<?php

namespace App\Http\Controllers\Booking;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\location\Country;
use App\Contracts\Auth\AuthContract;
use App\Http\Controllers\Controller;
use App\Contracts\Clinic\ClinicContract;
use App\Contracts\Doctor\DoctorContract;
use App\Http\Controllers\BaseController;
use App\Contracts\Patient\PatientContract;
use App\Contracts\Schedule\ScheduleContract;

class BookingController extends BaseController
{
    private $AuthContract;
    private $ClinicContract;
    private $DoctorContract;
    private $PatientContract;
    private $ScheduleContract;

    public function __construct(AuthContract $AuthContract, ClinicContract $ClinicContract, DoctorContract $DoctorContract,  PatientContract $PatientContract, ScheduleContract $ScheduleContract)
    {
        $this->AuthContract = $AuthContract;
        $this->ClinicContract = $ClinicContract;
        $this->DoctorContract = $DoctorContract;
        $this->PatientContract = $PatientContract;
        $this->ScheduleContract = $ScheduleContract;
    }

    public function doctorList($uuid)
    {
        $this->setPageTitle('Doctor List');
        $isUser = $this->ClinicContract->findId($uuid);
        $fetchDoctorList = $isUser->schedules;

        return view('admin.booking.doctor-list', compact('fetchDoctorList'));
    }

    public function clinicList()
    {
        $this->setPageTitle('Clinic List');
        $fetchClinicListData = [];
        $fetchDoctorId = '';
        if (auth()->user()->type == 'doctor' || auth()->user()->roles->first()->slug == 'doctor') {
            $fetchDoctorId = $this->DoctorContract->findId(auth()->user()->id);
            $fetchClinicList = $this->ScheduleContract->findDoctorToClinicId($fetchDoctorId->id);

            foreach ($fetchClinicList as $value) {
                $fetchClinicListData[] = $value->clinices;
            }
            $fetchDoctorId = $fetchDoctorId;
            // dd($fetchClinicListData);
        } else {
            $fetchClinicListData = $this->ClinicContract->getAll();
        }
        return view('admin.booking.clinic-list', compact('fetchClinicListData', 'fetchDoctorId'));
    }

    public function slotList($cid, $did)
    {
        $this->setPageTitle('Booking Slot');
        $fetchSchedule = Schedule::where('doctor_id', $did)->first();
        $fetchSchedule->slots;
        $getCountries = Country::all();
        return view('admin.booking.slot', compact('fetchSchedule', 'cid', 'getCountries'));
    }
}
