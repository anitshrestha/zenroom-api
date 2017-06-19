<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    protected $table    = 'hotels';
    protected $fillable = [
        'name',
        'slug',
        'about'
    ];

    /**
     * @return HasMany
     */
    public function details() : HasMany
    {
        return $this->hasMany(HotelRoomDetails::class, 'hotel_id', 'id');
    }
}
