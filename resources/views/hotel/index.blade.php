@extends('master')
@section('title', 'Home Page')

@section('content')
<h2 class="text-center">
    Hotel Lists
</h2>
@forelse($hotels as $hotel)
    <div class="list-group">
        <a href="{{ route('detail-page', ['slug' => $hotel->slug]) }}" class="list-group-item">
            <h4 class="list-group-item-heading">{{ $hotel->name }}</h4>
            <p class="list-group-item-text">{{ $hotel->about }}</p>
        </a>
    </div>
@empty
    <h3>No hotels please add one.</h3>
@endforelse
@endsection
