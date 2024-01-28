{{-- ia@dd($userData) --}}
<tr>
    <td>{{ $key + 1 }}</td>
    <td>
        {{ $userData?->users?->name ?? '' }}
    </td>
    <td>
        {{ $userData?->users?->email ?? '' }}
    </td>
    <td>
        {{ $userData?->users?->mobile_number ?? '' }}
    </td>
    <td>
        {{ $userData->users?->alternative_mobile_no ?? '' }}
    </td>
    <td>
        {{ $userData->doctorProfile?->doctorsCategories->implode('name',', ') ?? '' }}
    </td>
    <td>
        <div class="action_box">
            <a href="{{ route('admin.booking.booking.slot', [$userData->clinics_id, $userData->doctor_id]) }}"
                class="text-primary"><i class="fa fa-calendar" aria-hidden="true"></i></a>
        </div>
    </td>
</tr>
