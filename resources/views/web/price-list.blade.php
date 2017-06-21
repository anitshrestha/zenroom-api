
<div class="date-block text-center">
    <span class="date-holder glyphicon glyphicon-arrow-left"
        data-date="{{ $startDate->modify('-1 day')->format('Y-m-d') }}">
    </span>
    <span class="date-selector" data-date="{{ $endDate->modify('-1 day')->format('Y-m-d') }}">
        {{ $endDate->modify('-1 day')->format('M, Y') }}
    </span>
    <span class="date-holder glyphicon glyphicon-arrow-right"
        data-date="{{ $endDate->modify('+2 day')->format('Y-m-d') }}"></span>
</div>

<div class="table-responsive">
    
    <table class="table table-bordered" id="detail-table">
        
        <tr>
            <td class="first-td" rowspan="2">
                <div class="spanned"><strong>Price and Availability</strong></div>
            </td>
            @foreach($dateRange as $range)
                <td class="days {{ strtolower($range->format('l')) }}">{{ $range->format('l') }}</td>
            @endforeach
        </tr>
        
        <tr>
            @for($i = 1; $i <= iterator_count($dateRange); $i++)
                <td>{{ $i }}</td>
            @endfor
        </tr>

        @foreach ($roomTypes as $roomType)
        <tr class="{{ $roomType->slug }} room-block">
            <td class="first-td no-border">
                <div><strong>{{ $roomType->name }}</strong></div>
            </td>
            <td colspan="{{ iterator_count($dateRange) }}"
                class="no-border"></td>
        </tr>
        <tr>
            <td class="first-td">
                <div class="block"><strong>Room Available</strong></div>
            </td>
            @foreach($dateRange as $range)
                @php
                    $date = $range->format('Y-m-d');
                @endphp
                <td>
                    @if(isset($details[$roomType->id][$date]))
                    <a class="editable-block" href="#" data-name="available_rooms" data-type="text"
                        data-original-title="Edit Available Rooms"
                        data-pk="{{ $details[$roomType->id][$date]->id }}"
                        data-url="{{ route('update-details') }}">
                        {{ $details[$roomType->id][$date]->available_rooms }}
                    </a>
                    @else
                    ---
                    @endif
                </td>
            @endforeach
        </tr>

        <tr>
            <td class="first-td">
                <div class="block">
                    <strong>Price</strong>
                </div>
            </td>
            @foreach($dateRange as $range)
                @php
                    $date = $range->format('Y-m-d');
                @endphp
                <td>
                    @if(isset($details[$roomType->id][$date]))
                    <a class="editable-block" href="#" data-name="price" data-type="text"
                        data-original-title="Edit Price"
                        data-pk="{{ $details[$roomType->id][$date]->id }}"
                        data-url="{{ route('update-details') }}">
                        {{ $details[$roomType->id][$date]->price }}
                    </a>
                    @else
                    ---
                    @endif
                </td>
            @endforeach
        </tr>

        @endForeach
    </table>
</div>
