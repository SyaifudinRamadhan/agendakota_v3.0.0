@php
use Carbon\Carbon;
use App\Models\Organization;
@endphp
<table class="table table-borderless event-list">
    <thead>
        <tr>
            <th scope="col">Event Name</th>
            <th scope="col">
                &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
            </th>
            <th scope="col">Date&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
            <th scope="col">Ticket Price&emsp;&emsp;&emsp;</th>
            <th scope="col">Attendees&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
        </tr>
    </thead>
    <tbody class="mt-2">
        @foreach ($eventsTable as $event)
            <tr>
                <td><a
                        href="{{ route('organization.event.sessions', [$organizationID, $event->id]) }}">
                        <img class="img-table"
                            src="{{ asset('storage/event_assets/' . $event->slug . '/event_logo/thumbnail/' . $event->logo) }}">
                    </a></td>
                <td class="fontBold font-weight-bold">
                    <div class="teks-upcoming">
                        <p class="fs-11 mb-2">{{ $event->execution_type }}</p>
                    </div>
                    {{ $event->name }}
                </td>
                <td>
                    {{ Carbon::parse($event->start_date)->isoFormat('D MMMM') }} -
                    {{ Carbon::parse($event->end_date)->isoFormat('D MMMM') }}
                </td>
                <td id="target-harga-tiket2-{{ $event->id }}" class="price-table">Not Set</td>
                <td>
                    @php
                        $jumlahAttendes = 0;
                    @endphp
                    @foreach ($purchases as $purchase)
                        @if ($event->id == $purchase->event_id)
                            @php
                                $jumlahAttendes += 1;
                            @endphp
                        @endif
                    @endforeach
                    {{ $jumlahAttendes }} attendees
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
