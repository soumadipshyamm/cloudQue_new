@php
    $i = 1;
    $date = $date;
    // $processedSlots = [
    //     [
    //         'is_active' => 1,
    //         'schedule_id' => 27,
    //         'id' => 178,
    //         'start_time' => '09:00 PM',
    //         'end_time' => '11:00 PM',
    //         'interval' => '2.08 minutes',
    //         'person' => 30,
    //         'rac' => 5.0,
    //         'emergency' => 2.5,
    //     ],
    // ];

    // $breakTimeSlots = [
    //     [
    //         'is_active' => 1,
    //         'id' => 3,
    //         'break_from' => '09:10 PM',
    //         'break_to' => '09:20 PM',
    //         'schedule_id' => 27,
    //         'doctors_availabilitie_id' => 178,
    //     ],
    //     [
    //         'is_active' => 1,
    //         'id' => 4,
    //         'break_from' => '10:15 PM',
    //         'break_to' => '10:21 PM',
    //         'schedule_id' => 27,
    //         'doctors_availabilitie_id' => 178,
    //     ],
    // ];
    
@endphp


{{-- @dd($processedSlots) --}}
{{-- @dd($breakTimeSlots) --}}

@forelse($processedSlots as $key => $time)
    @php
        $formattedStartTime = \Carbon\Carbon::parse($time['start_time']);
        $formattedEndTime = \Carbon\Carbon::parse($time['end_time']);
        echo $interval = $time['interval'];
        echo $rac = $time['rac'];
        $emergency = $time['emergency'];
        $breakTime = $breakTimeSlots;
        $fetchBreakTime = slotbreaktime($breakTime);
    @endphp
    <div class="company_profiles card-body">
        <div class="stats_box row" id="bookingTimes">
            <div class="col-lg-12 col-md-12 ">
                <div class="row doctor-details-style clinicsheading_title">
                    <div class="col-md-4">
                        <div class="">
                            Schedule-{{ $i }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="">
                            Start Time-{{ \Carbon\Carbon::parse($time['start_time'])->format('h:i A') }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="">
                            End Time-{{ \Carbon\Carbon::parse($time['end_time'])->format('h:i A') }}
                        </div>
                    </div>
                </div>
            </div>
            @foreach (\Carbon\CarbonPeriod::create($formattedStartTime, $interval, $formattedEndTime) as $k => $times)
                @php
                    $isInBreak = false;
                    foreach ($breakTime as $break) {
                        $breakFrom = \Carbon\Carbon::parse($break['break_from']);
                        $breakTo = \Carbon\Carbon::parse($break['break_to']);
                        if ($times->betweenIncluded($breakFrom, $breakTo)) {
                            $isInBreak = true;
                            break;
                        }
                    }

                    if (!$isInBreak) {
                        $slotColor = '#df4508'; // Default color for unavailable slots
                        $isDisabled = true;
                        if ($time['person'] - $rac <= $k) {
                            $slotColor = '#f5d1a2'; // RAC Slot
                            $isDisabled = true;
                        } elseif ($k <= $emergency) {
                            $slotColor = '#8aaeed'; // Emergency Slot
                            $isDisabled = false;
                        } else {
                            $slotColor = '#7df0ae'; // Available Slot
                            $isDisabled = true;
                        }
                        echo '<div class="col">';
                        echo '<div class="slot_statsb_single" style="background-color:' . $slotColor . '">';
                        echo '<input type="hidden" id="time' . $key . '-' . $k . '" name="slotId" value="' . $time['id'] . '">';
                        echo '<input id="time' . $key . '-' . $k . '" type="radio" name="booking_time" value="' . $date . ' ' . $times->format('G:i:s') . '" class="booking_time"' . ($isDisabled ? ' disabled' : '') . '>';
                        echo $k;
                        echo '<label for="time' . $key . '-' . $k . '" class="slot_text-dsgn">' . $times->format('h:i A') . '</label>';
                        echo '</div>';
                        echo '</div>';
                    }
                @endphp
            @endforeach

        </div>
    </div>
    @php
        $i++;
    @endphp
    {{-- @endif --}}
@empty
    <div class="col">
        <p>Unavailable Slots</p>
    </div>
@endforelse
