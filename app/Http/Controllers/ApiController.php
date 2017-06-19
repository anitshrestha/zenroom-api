<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

use App\LogicValidators\DataValidator;
use App\Repositories\ZenRoomRepository;

class ApiController extends Controller
{
    private $hotelRepository;

    public function __construct ( ZenRoomRepository $hotelRepository) {
        $this->hotelRepository = $hotelRepository;
    }

    public function getHotelsList() : JsonResponse {
        return response()->json( 
            $this->hotelRepository->getHotelsList()
        );
    }
    
    public function getHotelRoomTypes() : JsonResponse {
        return response()->json( 
            $this->hotelRepository->getHotelRoomTypes() 
        );
    }

    public function createHotel( Request $request ) : JsonResponse {
        $params = $request->only([
            'name',
            'about',
        ]);

        $return = json_decode( $this->hotelRepository->createHotel($params));
        
        return response()->json(
            $return->message,
            $return->status_code);
    }

    public function createHotelType( Request $request ) : JsonResponse {
        $params = $request->only([
            'name'
        ]);

        $return = json_decode($this->hotelRepository->createHotelType($params));
        
        return response()->json(
            $return->message,
            $return->status_code);
    }

    public function getHotelWithRoomsAndPrice(
        string $slug,
        string $date,
        DataValidator $dataValidator
    ) : JsonResponse {

       return $this->hotelRepository->getHotelWithRoomsAndPrice(
            $slug,
            $date,
            $dataValidator
        );
    }

    public function addHotelRoomsPriceAndType(
        Request $request,
        string $slug,
        DataValidator $dataValidator
    ) : JsonResponse {

        $params = $request->only([
            'room_type_id',
            'available_rooms',
            'price',
            'start_date',
            'end_date',
            'filtered_days'
        ]);

        return $this->hotelRepository->addHotelRoomsPriceAndType( 
            $params + ['slug' => $slug],
            $dataValidator
            );
    }

}// class close
