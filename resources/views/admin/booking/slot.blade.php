@extends('layouts.app', ['isSidebar' => true, 'isNavbar' => true, 'isFooter' => true])
@section('booking', 'active')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables/responsive.bootstrap4.min.css') }}">
@endpush
@section('content')
    <div class="dashboard_mainsec">
        <!-- company Details -->
        <div class="companydetails_head">
            <h3 class="heading_title">
                {{ $pageTitle }}
            </h3>
            <div class="comdehead_right">
            </div>
        </div>
    </div>
    <!-- advance filter box -->

    <!-- company details -->
    {{-- @php
        $today = \Carbon\Carbon::today();
        $daysDifference = $today->diffInDays('2024-01-30');
    @endphp --}}
    <form action="" method="post">
        <div class="company_profiles card-body">
            <div class="row">
                <div class="col-6">
                    {{-- <h5>Clinic Name:
                    {{ $fetchSchedule?->profileClinic?->clinic_name ?? '' }}
                </h5> --}}
                    <input type="hidden" class="clinicId" value="{{ $fetchSchedule?->profileClinic?->id ?? '' }}">
                    <input type="hidden" class="doctoreId" value="{{ $fetchSchedule?->users?->id ?? '' }}">
                </div>
                <div class="col-6">
                    {{-- <h5>Doctor Name:
                    {{ $fetchSchedule?->users?->name ?? '' }}
                </h5> --}}
                </div>
            </div>
            <div class="div-type-radio-style">
                <div class="col-4">
                    <div class="type-radio-style">
                        <input type="radio" name="type" class="slot-type-input" value="1">
                        <label>Exsisting Patient</label>
                    </div>
                </div>
                {{-- <div class="type-radio-style">
                <div class="form-group">
                    <label>Exsisting Patient</label>
                    <input type="radio" name="type" class="slot-type-input" value="1">
                </div>
            </div> --}}
                <div class="col-4">
                    <div class="type-radio-style">
                        <input type="radio" name="type" class="slot-type-input" value="2">
                        <label>Add New Patient</label>
                    </div>
                </div>
            </div>
            <div class="div-patient-list" id="div-patient-list">
                <div class="col-md-6">
                    <label>Patient No</label>
                    <input type="text" name="" class="form-control" placeholder="Enter Patient No">
                </div>
            </div>
            <div class="div-patient-add" id="div-patient-add">
                <form action="{{ route('admin.patient.add') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="uuid" id="uuid" value="{{ $isPatient->uuid ?? '' }}">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 ">
                            <div class="doctor-details-style clinicsheading_title">
                                Patient Details
                            </div>
                        </div>
                        <div class="col-md-4 adfilter-single">
                            <label for="">Name</label>
                            <input type="text" class="form-control" name="name" id="name"
                                placeholder="Enter Patient Name" value="{{ old('name', $isPatient->name ?? '') }}">
                            @error('name')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4 adfilter-single">
                            <label for="">Email</label>
                            <input type="email" class="form-control" name="email" id="email"
                                placeholder="Enter Patient Email" value="{{ old('email', $isPatient->email ?? '') }}">
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4 adfilter-single">
                            <label for="">Password</label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Enter Patient Password">
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        @php
                            $gender = old('gender') ?? (isset($isPatient) ? $isPatient->gender : '');
                        @endphp
                        <div class="col-md-4 adfilter-single">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" class="form-control">
                                <option value="">---Select Gender---</option>
                                <option value="male" @selected($gender == 'male')>Male</option>
                                <option value="female" @selected($gender == 'female')>Female</option>
                                <option value="other" @selected($gender == 'other')>Other</option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4 adfilter-single">
                            <label for="">Phone</label>
                            <input type="number" class="form-control" name="phone" id="phone"
                                placeholder=" Enter Doctor Phone"
                                value="{{ old('phone', $isPatient->mobile_number ?? '') }}">
                            @error('phone')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4 adfilter-single">
                            <label for="alternative_mobile_no">Alternative Phone Number</label>
                            <input type="number" class="form-control" name="alternative_mobile_no"
                                id="alternative_mobile_no" placeholder="Ent Doctor Alternative Phone Number"
                                value="{{ old('alternative_mobile_no', $isPatient->alternative_mobile_no ?? '') }}">
                            @error('alternative_mobile_no')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4 adfilter-single">
                            <label for="">Date of Birth</label>
                            <input type="date" class="form-control" name="dob" id="dob"
                                value="{{ old('dob', $isPatient->patientProfile?->dob ?? '') }}">
                            @error('dob')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        @php
                            $bloodGroup = old('blood_group') ?? (isset($isPatient) ? $isPatient->patientProfile?->blood_group : '');
                        @endphp
                        <div class="col-md-4 adfilter-single">
                            <label for="">Blood Group</label>
                            <select name="blood_group" id="blood_group" class="form-control">
                                <option value="">---Select Blood Group---</option>
                                <option value="a+" @selected($bloodGroup == 'a+')>A+</option>
                                <option value="b+" @selected($bloodGroup == 'b+')>B+</option>
                                <option value="ab" @selected($bloodGroup == 'ad')>AB</option>
                            </select>
                            @error('blood_group')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        @php
                            $allergyMedicine = old('allergy_medicine') ?? (isset($isPatient) ? $isPatient->patientProfile?->allergy_medicine : '');
                        @endphp
                        <div class="col-md-4 adfilter-single">
                            <label for="">Allergy Medicine</label>
                            <input type="radio" class="allergyMedicine" name="allergy_medicine"
                                id="allergy_medicine_yes" value="yes" @checked($allergyMedicine == 'yes')>
                            <label for="">Yes</label>
                            <input type="radio" class="allergyMedicine" name="allergy_medicine"
                                id="allergy_medicine_no" value="no" @checked($allergyMedicine == 'no')>
                            <label for="">No</label>
                            @error('allergy_medicine')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        @php
                            $iscountry = old('country_id') ?? (isset($isPatient) ? $isPatient->country_id : '');
                        @endphp
                        <div class="col-md-4 adfilter-single">
                            <label for="">Country</label>
                            <select name="country_id" class="form-control select_country" id="select_country">
                                <option value="">---Select---</option>
                                @foreach ($getCountries as $country)
                                    <option value="{{ $country->id }}" @selected($iscountry == $country->id)>{{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country_id')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        @php
                            $isstate = old('state_id') ?? (isset($isPatient) ? $isPatient->state_id : '');
                        @endphp
                        <input type="hidden" name="state" value="{{ $isstate }}" class="select_state_name">
                        <div class="col-md-4 adfilter-single">
                            <label for="">State</label>
                            <select name="state_id" id="" class="form-control select_state">
                                <option value="">---Select---</option>
                            </select>
                            @error('state_id')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        @php
                            $iscity = old('city_id') ?? (isset($isPatient) ? $isPatient->city_id : '');
                        @endphp
                        <input type="hidden" name="state" value="{{ $iscity }}" class="select_city_name">
                        <div class="col-md-4 adfilter-single">
                            <label for="">City</label>
                            <select name="city_id" id="" class="form-control select_city">
                                <option value="">---Select---</option>
                            </select>
                            @error('city_id')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 adfilter-single">
                            <label for="">Address</label>
                            <textarea name="address" id="address" cols="30" rows="3" class="form-control">{{ old('address', $isPatient->address ?? '') }}</textarea>
                            @error('address')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 adfilter-single">
                            <label for="">Description</label>
                            <textarea name="description" id="description" cols="30" rows="3" class="form-control" readonly> {{ old('description', $isPatient->patientProfile?->description ?? '') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 adfilter-single">
                            <label for="">Profile Images</label>
                            <input type="file" class="form-control" name="profile_images" id="profile_images"
                                placeholder="">
                            @error('profile_images')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        {{-- <div class="modal-footer">
                        <button type="button" class="btn btn-cancle">Cancel</button>
                        <button type="submit" class="btn btn-book">Submit</button>
                    </div> --}}
                    </div>
                </form>
            </div>
        </div>
        @php
            $today = \Carbon\Carbon::today();
            $daysDifference = $today->diffInDays($fetchSchedule->valid_date);
        @endphp
        <div class="company_profiles card-body">
            <div class="stats_box row">
                @foreach (range(0, $daysDifference) as $i)
                    @php
                        $date = \Carbon\Carbon::today()->addDays($i);
                    @endphp
                    @if (checkAvalableSchedul($fetchSchedule->id, $date))
                        <div class="col-2">
                            <div class="statsb_single">
                                <input type="radio" id="date{{ $i }}" name="booking_date"
                                    value="{{ $date->format('Y-m-d') }}" class="booking_date">
                                <label for="date{{ $i }}" class="statmain_box">
                                    {{ $date->format('D') }}
                                    <h6>{{ $date->format('d') }}</h6>
                                    {{ $date->format('M') }}
                                </label>
                            </div>
                        </div>
                    @else
                        <div class="col-2">
                            <div class="statsb_single bg-secondary">
                                <input type="radio" id="date{{ $i }}" name="booking_date"
                                    value="{{ $date->format('Y-m-d') }}" class="booking_date" disabled>
                                <label for="date{{ $i }}" class="statmain_box">
                                    {{ $date->format('D') }}
                                    <h6>{{ $date->format('d') }}</h6>
                                    {{ $date->format('M') }}
                                </label>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        {{-- <div class="company_profiles card-body">
        <div class="stats_box row">
            <div class="col-4">
                <div class="d-flex align-items-center">
                    <div class="bg-Info p-2 rounded-circle" style="width:30px;height:30px">
                    </div>
                    <p class="m-0 ml-2">Available</p>
                </div>
            </div>
            <div class="col-4">
                <div class="d-flex align-items-center">
                    <div class="bg-Danger p-2 rounded-circle" style="width:30px;height:30px">
                    </div>
                    <p class="m-0 ml-2">Emergency</p>
                </div>
            </div>
            <div class="col-4">
                <div class="d-flex align-items-center">
                    <div class="bg-Warning p-2 rounded-circle" style="width:30px;height:30px">
                    </div>
                    <p class="m-0 ml-2">RAC</p>
                </div>
            </div>
        </div>
    </div> --}}
        <div class="stats_box row" id="bookingTimes">
            <div class="time_row" style="display:none;">
                <!-- Booking times will be populated dynamically -->
            </div>
        </div>
        <div class="modal-footer">
            {{-- <button type="button" class="btn btn-cancle">Cancel</button> --}}
            <input type="submit" class="btn btn-book" value="submit">
        </div>
    </form>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/custom/address.js') }}"></script>

    <script>
        $('.allergyMedicine').on('change', function(e) {
            // $e.preventDefault();
            $('#description').prop('readonly', true);
            let data = $(this).val();
            if (data == 'yes') {
                $('#description').prop('readonly', false);
            }
        });


        var baseUrl = APP_URL + "/";
        $(document).ready(function() {
            var getClinicData = $('.clinicId').val();
            var getDoctorId = $('.doctoreId').val();
            $(document).on("change", ".booking_date", function() {
                var selectedDate = $(this).val();
                // alert(selectedDate + "/" + getClinicData + "/" + getDoctorId)
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });
                $.ajax({
                    url: baseUrl + "admin/schedule/get-booking-times",
                    method: 'POST',
                    data: {
                        date: selectedDate,
                        doctorId: getDoctorId,
                        clinicId: getClinicData
                    },
                    // dataType: 'json',
                    success: function(response) {
                        $("#bookingTimes").html(response);
                        $('.time_row').fadeIn();
                    },
                    error: function(error) {
                        console.error('Error fetching booking times:', error);
                    }
                });
            });
            $('#div-patient-list').hide();
            $('#div-patient-add').hide();
        });

        $(document).on('click', '.slot-type-input', function() {
            let type = $(this).val();
            if (type == 1) {
                $('#div-patient-add').hide();
                $('#div-patient-list').show();
            } else {
                $('#div-patient-add').show();
                $('#div-patient-list').hide();
            }
        })
    </script>
@endpush
