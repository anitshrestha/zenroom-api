@extends('master')
@section('title', $hotel->name . ' Details')

@section('styles')
<link rel="stylesheet" href="{{ elixir('/css/list.css') }}">
@endsection
 Z
@section('content')
<div id="home-container">
    <div class="row">
        <h4>Bulk Operation</h4>
        <div class="well">
            <form method="POST" class="store-details" action="{{ route('store-details', ['slug' => $hotel->slug]) }}">
                <div class="row form-group">
                        <div class="col-sm-3 form-contents">
                            <label>Select Room Type</label>
                            <select class="form-control" name="room_type_id">
                                <option value="">--Please select--</option>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    <div class="clearfix"></div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-3 form-contents">
                        <label>Select Date Range</label>
                        <input class="date-input form-control from_date" type="text" name="from_date" placeholder="Date From">
                        <br //>
                        <input class="date-input form-control to_date" type="text" name="to_date" placeholder="Date To">
                    </div>
                    <div class="col-sm-3 form-contents">
                        <label class="center-block">Refine Days</label>
                        @foreach(collect(config('days.all_days'))->chunk(3) as $chunk)
                            @foreach($chunk as $key => $value)
                                <div class="row col-md-7">
                                    <span class="checkbox">
                                        <input id="input-{{ $key }}" class="document-input checkbox-input" name="days[]"
                                            type="checkbox" value="{{ $key }}">
                                        <label for="input-{{ $key }}">{{ $value }}</label>
                                    </span>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-3 form-contents">
                        <label>Add room availability and price</label>
                        <input class="form-control" type="number" name="available_rooms" placeholder="Room Available"><br />
                        <input class="form-control" type="number" name="price" placeholder="Price">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 form-contents">
                        <a href="{{ route('index-page') }}" class="btn btn-default">Cancel</a>
                        <button class="btn btn-success" type="submit">Save/Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <h3 class="text-danger">
            <strong>{{ $hotel->name }}</strong>
        </h3>
        <div class="page-navigation">
            <div class="btn-arrow right pull-right">
                <i class="glyphicon glyphicon-arrow-right"></i>
            </div>
            <div class="btn-arrow left">
                <i class="glyphicon glyphicon-arrow-left"></i>
            </div>
        </div>

        <div id="main-content">
            @include('hotel.detail', [
                'details' => $details,
                'hotelId' => $hotel->id,
                'dateRange' => $dateRange,
                'roomTypes' => $roomTypes,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ])
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ elixir('/js/vendor/notify.min.js') }}"></script>
<script src="{{ elixir('/js/vendor/table-fixer.js') }}"></script>
<script>
    HotelContainer.xhrLink = '{{ route('get-detail-date', ['slug' => $hotel->slug]) }}';
</script>
<script src="{{ elixir('/js/list.js') }}"></script>
@endsection
