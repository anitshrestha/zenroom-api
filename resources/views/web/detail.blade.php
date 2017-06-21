@extends('master')
@section('title', 'hotel->name' . ' Details')

@section('styles')
<link rel="stylesheet" href="{{ elixir('/css/list.css') }}">
@endsection

@section('content')
<div id="home-container">
    <div class="row">
        <h4>Bulk Operation {{ elixir('/css/list.css') }}</h4>
        <div class="well

            <form method="POST" class="store-details">
                <div class="row form-group">
                        <div class="col-sm-3 form-contents">
                            <label>Select Room Type</label>
                            <select class="form-control" name="room_type_id">
                                <option value="">--Please select--</option>
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
                        <a href="" class="btn btn-default">Cancel</a>
                        <button class="btn btn-success" type="submit">Save/Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <h3 class="text-danger">
        <strong>HOTEL NAME</strong>
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
           
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ elixir('/js/vendor/notify.min.js') }}"></script>
<script src="{{ elixir('/js/vendor/table-fixer.js') }}"></script>

<script>
    HotelContainer.slug = '{{$slug}}';
</script>

<script src="{{ elixir('/js/ZenRoom.js') }}"></script>
<script src="{{ elixir('/js/detail-page.js') }}"></script>

@endsection
