<?php

namespace App\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

use App\LogicValidators\DataValidator;
use App\Models\Hotel;
use App\Models\HotelRoomDetail;
use App\Models\HotelRoomType;

class ZenRoomRepository
{
    protected $hotel;
    protected $hotelRoomDetail;
    protected $hotelRoomType;

    public function __construct(
        Hotel $hotel,
        HotelRoomDetail $hotelRoomDetail,
        HotelRoomType $hotelRoomType

    ) {
        $this->hotel = $hotel;
        $this->hotelRoomDetail = $hotelRoomDetail;
        $this->hotelRoomType = $hotelRoomType;
    }

    /**
     * Get all hotels
     *
     * @return Collection
     */
    public function getHotelsList() : Collection {
     return $this->hotel->get();   
    }

    /**
     * Get hotel rooms and their price by slug and date 
     *
     * @return JsonResponse
     */
    public function getHotelWithRoomsAndPrice(
        string $slug,
        string $date,
        DataValidator $dataValidator
    ) : JsonResponse {
        try {
            $dataValidator->validateDate(array('date'=>$date));
            
            $startDate = "";
            $endDate = "";
            $startDate  = new \DateTime('first day of this month ' . $date);
            $endDate  = (new \DateTime('last day of this month ' . $date));

            $hotelId = $this->hotel->whereSlug($slug)
            ->get()->pluck('id');

            $return  = $this->hotelRoomDetail
                ::with( 'roomType' )
                ->where('hotel_id', $hotelId[0])
                ->whereDate('available_date', '>=', $startDate)
                ->whereDate('available_date', '<=', $endDate)
                ->orderBy('available_date')
                ->get();

             return response()->json(
                json_encode($return),
                200
            );
        } catch (Exception $e ) {
            //note log exception
            return response()->json(
                $e->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Get All hotel room Types
     *
     * @return Collection
     */
    public function getHotelRoomTypes() : Collection {
        return $this->hotelRoomType->get();
    }

    /**
     * Return iterable date period bet
     *
     * @param string $startDate Date string in format 'YYYY-MM-DD'
     *
     * @param string $endDate Date string in format 'YYYY-MM-DD'
     *
     * @return DatePeriod
     */
    private function findDatePeriod (
        string $startDate,
        string $endDate
    ):\DatePeriod {
        //validation to check 
        $begin = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        // note: add one day so as to include the end date of our range
        $end = $end->modify( '+1 day' ); 
        //note: 1 Day
        $interval = new \DateInterval('P1D');

        return new \DatePeriod($begin, $interval, $end);
    }
    
    public function createHotel(array $params) : string {
        // var_dump($params); die;
        $this->hotel->name = $params['name'];
        $this->hotel->slug = preg_replace('/\s+/', '-', $params['name']);
        $this->hotel->about = $params['about'];
        $return  = [];
        
        try {

            if( $this->hotel->save() ) {
                $return = [
                'status_code' => 201,
                'message' => 'New Hotel Created Successfully!!'];
            } 

        } catch( Exception $e ) {

          echo $e->getMessage(); die; 
            if( $e->getCode() == 23000 ) {
                $statusCode = Response::HTTP_BAD_REQUEST;
                $message = 'New Hotel Could Not Be Added. Hotel Name Can Not Be Same!!';
            
            } else {
                $statusCode = Response::HTTP_BAD_REQUEST;
                $message = 'New Hotel Could Not Be Added. Something Went Wrong!!';
                
                //needs to be logged
                // $e->getMessage();
            }

            $return = [
                'status_code' => $statusCode,
                'message' => $message
            ];
        }

        return json_encode($return);
    }

    /**
     * [createHotelType description]
     * 
     * @param  array  $params [description]
     * 
     * @return [type]         [description]
     */
    public function createHotelType(array $params) : string
    {
        $this->hotelRoomType->name = $params['name'];
        $this->hotelRoomType->slug = preg_replace('/\s+/', '-', $params['name']);
        $return  = [];
        
        try {

            if( $this->hotelRoomType->save() ) {
                $return = [
                'status_code' => 201,
                'message' => 'New Hotel Type Created Successfully!!'];
            } 

        } catch( Exception $e ) {

            if( $e->getCode() == 23000 ) {
                $statusCode = 409;
                $message = 'New Hotel Could Not Be Added. Hotel Type Already Exists!!';
            
            } else {
                $statusCode = 400;
                $message = 'New Hotel Type Could Not Be Added. Something Went Wrong!!';
                
                //needs to be logged
                // $e->getMessage();
            }

            $return = [
                'status_code' => $statusCode,
                'message' => $message
            ];
        }

        return json_encode($return);
    }

    /**
     * Save room Date range and price
     *
     * @param array $params
     */
    public function addHotelRoomsPriceAndType(
        array $params,
        DataValidator $dataValidator
    ) : JsonResponse {

        try {
            $dataValidator->validatehotelRoomsDateRangePriceAndType(
                $params
            );

            $unSelectedDays = [];
            
            if ( isset($params['filtered_days']) ) {
                $unSelectedDays = explode(",", $params['filtered_days']);
            }

            $period = $this->findDatePeriod(
                $params['start_date'], 
                $params['end_date']
            );
            
            $hotelId = $this->hotel->whereSlug( $params['slug'] )
                ->get()->pluck('id');

            \DB::beginTransaction();

            foreach ($period as $dates) {

                if (
                    !in_array(
                            $dates->format('N'),
                            $unSelectedDays
                    )
                ) {
                    $this->hotelRoomDetail->updateOrCreate(
                        [
                            'available_date' => $dates->format('Y-m-d'),
                            'hotel_id' => $hotelId[0],
                            'room_type_id' => $params['room_type_id'],
                       ],
                       [
                            'available_rooms' => $params['available_rooms'],
                            'price' => $params['price']
                       ]
                    );
                }
            }

            \DB::commit();

            return response()->json('',
                204 
            );

        } catch ( \Exception $e ) {
            // echo $e->getMessage();
            \DB::rollback();
            return response()->json(
                $e->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        }
    }

}//class close
