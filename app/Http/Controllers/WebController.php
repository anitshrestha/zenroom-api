<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class WebController extends Controller
{
    public function detail(string $slug) : View {

    	  return view('hotel.list', ['slug' => $slug]);
    }
    
     
}
