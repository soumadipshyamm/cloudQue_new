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
                Clinics List
            </h3>
        </div>
    </div>
    <!-- advance filter box -->
    <!-- company details -->
    <div class="company_profiles card-body">
        <div class="table-responsive adminbio_table">
            <table class="table table-hover dataTable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        {{-- <th scope="col">Image</th> --}}
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone No.</th>
                        <th scope="col">Alternative Ph No.</th>
                        <th scope="col">Address</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fetchClinicListData as $key=>$clinic)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                {{ $clinic->clinic_name ?? '' }}
                            </td>
                            <td>
                                {{ $clinic->email ?? '' }}
                            </td>
                            <td>
                                {{ $clinic->phone ?? '' }}
                            </td>
                            <td>
                                {{ $clinic->alt_phone ?? '' }}
                            </td>
                            <td class="brk_line" >
                               <p> {{ $clinic->address ?? '' }}</p>
                            </td>
                            <td>
                                <div class="action_box">
                                    @if (auth()->user()->hasRole('super-admin', 'clinic'))
                                        <a href="{{ route('admin.booking.doctor.list', $clinic->uuid) }}"
                                            class="text-primary"><i class="fa-solid fa-user-doctor"></i></a>
                                    @endif
                                    @if (auth()->user()->hasRole('doctor'))
                                        <a href="{{ route('admin.booking.booking.slot', [$clinic->id, $fetchDoctorId->id]) }} --}}"
                                            class="text-primary"><i class="fa fa-calendar" aria-hidden="true"></i></a>
                                    @endif
                                </div>
                                {{-- {{ route('admin.booking.booking.slot', [$userData->clinics_id, $userData->doctor_id]) }} --}}
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>
@endsection
@push('scripts')
@endpush
