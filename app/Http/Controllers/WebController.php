<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class WebController extends Controller
{

    public function __construct() {}

    public function index()
    {
        //call detail api and pass data into view
        //
        // return view('hotel.index', [
        //     'hotels' => $this->hotelRepository->getAllHotels()
        // ]);
    }


}
