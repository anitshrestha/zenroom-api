<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelRoomType extends Model
{
    protected $table    = 'hotels_room_type';
    protected $fillable = [
        'name',
        'slug'
    ];
}
