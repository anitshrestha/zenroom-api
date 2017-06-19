<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelRoomDetail extends Model
{
    protected $table    = 'hotels_room_details';
    protected $fillable = [
        'available_date',
        'available_rooms',
        'hotel_id',
        'room_type_id',
        'price'
    ];
    
    protected $dates = [
        'available_date',
        'created_at',
        'updated_at'
    ];

    /**
     * @return BelongsTo
     */
    public function hotel() : BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function roomType() : BelongsTo
    {
        return $this->belongsTo(HotelRoomType::class, 'room_type_id', 'id');
    }
}
