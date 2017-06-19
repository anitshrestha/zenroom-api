<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery as m;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Hotel;
use App\Models\HotelRoomDetail;
use App\Models\HotelRoomType;
use App\Repositories\ZenRoomRepository as ZenRoomRepository;


/**
 * @coversDefaultClass App\Repos\ZenRoomRepository
 */
class ZenRoomRepositoryTest extends TestCase
{
    protected $hotelMock;
    protected $hotelRoomDetailMock;
    protected $hotelRoomTypeMock;
    protected $zenRoomRepository;
    
    public function setUp()
    {
        parent::setUp();

        // $user = factory(App\User::class)->make();
        
        $this->hotelMock = m::mock(Hotel::class);
        $this->hotelRoomDetailMock = m::mock(HotelRoomDetail::class);
        $this->hotelRoomTypeMock = m::mock(HotelRoomType::class);
    
        $this->zenRoomRepository = new ZenRoomRepository(
            $this->hotelMock,
            $this->hotelRoomDetailMock,
            $this->hotelRoomTypeMock
        );
    }

   /**
    * @covers ::getAllHotels
    */
    public function XtestGetAllHotelsReturnsCollectionAndContainsRequiredResult()
    {
        $this->hotelMock
            ->shouldReceive('get')
            ->andReturn(
                new Collection(['Hotel 1', 'Hotel 2'])
            );

        $result = $this->hotelRepository->getAllHotels();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals('Hotel 1', $result->first());
    }

    /**
     * @covers ::getHotelBySlug
     *
     * @expectedException \App\Exceptions\HotelNotFoundException
     */
    public function XtestGetHotelBySlugThrowsException()
    {
        $this->hotelMock
            ->shouldReceive('where')
            ->with('slug', 'test-slug')
            ->andReturnSelf()
            ->shouldReceive('get')
            ->andReturn(new Collection);

        $this->hotelRepository->getHotelBySlug('test-slug');
    }

    /**
     * @covers ::getHotelBySlug
     */
    public function XtestGetHotelReturnsCollectionAndHotel()
    {
        $this->hotelMock
            ->shouldReceive('toArray')
            ->andReturn([$this->hotelMock]);

        $this->hotelMock
            ->shouldReceive('where')
            ->with('slug', 'test-slug')
            ->andReturnSelf()
            ->shouldReceive('get')
            ->andReturn(
                new Collection(
                    $this->hotelMock
                )
            );

        $result = $this->hotelRepository->getHotelBySlug('test-slug');

        $this->assertInstanceOf(Hotel::class, $result);
    }

   /**
    * @covers ::getAllHotelRoomTypes
    */
    public function XtestGetAllHotelRoomTypesReturnsCollectionAndContainsRequiredResult()
    {
        $this->hotelMock
            ->shouldReceive('get')
            ->andReturn(
                new Collection(['Room Type 1', 'Room Type 2'])
            );

        $result = $this->hotelRepository->getAllHotels();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals('Room Type 1', $result->first());
    }

    /**
     * @covers ::getHotelDetails
     */
    public function XtestGetHotelDetailsReturnArray()
    {
        $this->hotelRoomDetailMock
            ->shouldReceive('where')->with('hotel_id', 1)
            ->andReturnSelf()
            ->shouldReceive('whereDate')->with('available_date', '>=', '2016-12-01')
            ->andReturnSelf()
            ->shouldReceive('whereDate')->with('available_date', '<=', '2016-12-31')
            ->andReturnSelf()
            ->shouldReceive('orderBy')->with('available_date')
            ->andReturnSelf()
            ->shouldReceive('get')
            ->andReturn([]);

        $result = $this->hotelRepository->getHotelDetails(1, '2016-12-01', '2016-12-31');

        $this->assertInternalType('array', $result);
    }

    /**
     * @covers ::storeDetails
     */
    public function xtestStoreDetailsStroresAllDetails()
    {
        $this->hotelRoomDetailMock = $this->getMockBuilder(HotelRoomDetail::class)
             ->setMethods(['updateOrCreate'])
             ->getMock();

        $this->hotelRoomDetailMock
            ->expects($this->exactly(2))
            ->method('updateOrCreate')
            ->withConsecutive(
                [
                    [
                        'available_date' => '2016-12-04',
                        'hotel_id' => 1,
                        'room_type_id' => 1
                    ], [
                        'available_rooms' => 1,
                        'price' => 1500,
                    ]
                ],
                [
                    [
                        'available_date' => '2016-12-11',
                        'hotel_id' => 1,
                        'room_type_id' => 1
                    ], [
                        'available_rooms' => 1,
                        'price' => 1500,
                    ]
                ]
            );

        $this->hotelMock
            ->shouldReceive('toArray')
            ->andReturn([$this->hotelMock])
            ->shouldReceive('getAttribute')
            ->andReturn(1);

        $this->hotelMock
            ->shouldReceive('where')->with('slug', 'test-slug')
            ->andReturnSelf()
            ->shouldReceive('get')
            ->andReturn(
                new Collection(
                    $this->hotelMock
                )
            );

        //Explicit call due to need of passing a mocked object
        $detail = new ZenRoomRepository(
            $this->hotelMock,
            $this->hotelRoomDetailMock,
            $this->hotelRoomTypeMock
         );

        $result = $detail->storeRoomDetails([
            'from_date' => '2016-12-01',
            'to_date' => '2016-12-12',
            'days' => ['Sunday' => 'Sundays'],
            'room_type_id' => 1,
            'available_rooms' => 1,
            'price' => 1500,
            'hotel_id' => 1,
            'slug' => 'test-slug'
        ]);

        $this->assertNull($result);
    }

   /**
    * @covers ::update
    */
    public function XtestUpdateCanUpdateAllDetails()
    {
        $this->hotelRoomDetailMock->shouldReceive('findOrFail')->with(1)
            ->andReturnSelf()
            ->shouldReceive('update')->with(['price' => 500])
            ->andReturn(true);

        $result = $this->hotelRepository->update([
            'pk' => 1,
            'name' => 'price',
            'value' => 500
        ]);

        $this->assertInternalType('boolean', $result);
    }


    public function testCreateNewHotelsSuccessfully()
    {
        $this->zenRoomRepository = new ZenRoomRepository(
            new Hotel(),
            $this->hotelRoomDetailMock,
            $this->hotelRoomTypeMock
        );

        $fillable = [
            'name' => 'test 4',
            'about' => 'about test 2 hotel'
        ];

        $result = $this->zenRoomRepository->createHotels($fillable);
        
        $result  = json_decode($result);

        $this->assertEquals($result->status_code, 201);
    }

    public function testCreateNewHotelTypeSuccessfully()
    {
        $this->hotelMock
            ->shouldReceive('toArray')
            ->andReturn([$this->hotelMock])
            ->shouldReceive('getAttribute')
            ->andReturn(1);

        $this->hotelMock
            ->shouldReceive('where')->with('slug', 'test-slug')
            ->andReturnSelf()
            ->shouldReceive('get')
            ->andReturn(
                new Collection(
                    $this->hotelMock
                )
            );

        $this->zenRoomRepository = new ZenRoomRepository(
            $this->hotelMock,
            $this->hotelRoomDetailMock,
            new HotelRoomType()
        );

        $fillable = [
            'name' => 'type 1',
        ];

        $result = $this->zenRoomRepository->createHotelType($fillable);
        
        $result  = json_decode($result);

        $this->assertEquals($result->status_code, 201);
    }

    public function testGetHotelsWithDetails()
    {
        $this->zenRoomRepository = new ZenRoomRepository(
            new Hotel(),
            new HotelRoomDetail(),
            $this->hotelRoomTypeMock
        );

        $result = $this->zenRoomRepository->getHotelWithRoomsAndPrice(
            'kathmandu-hotel', 
            '2017-06-04',
            \App::make('App\LogicValidators\DataValidator')
            );

        // $result = json_decode($result->getData());

        $this->assertEquals($result->getStatusCode(), 200);
    }

    public function testGetDatePeriodSuccessfully_WithValidData() {
        $detail = new ZenRoomRepository(
            $this->hotelMock,
            $this->hotelRoomDetailMock,
            $this->hotelRoomTypeMock
         );

        $class = new \ReflectionClass($detail);
        $method = $class->getMethod("findDatePeriod");
        $method->setAccessible(true);

        $result = $method->invokeArgs(
            $detail, 
            array('2016-12-01', '2016-12-05')
        );

        $this->assertInstanceOf(\DatePeriod::class, $result);
    }

    public function testInsertHotelRoomPriceAndTypeWithAvailableDateSuccessfully_WithValidData()
    {     
        $detail = new ZenRoomRepository(
            new Hotel(),
            new HotelRoomDetail(),
            $this->hotelRoomTypeMock
         );

        $result = $detail->addHotelRoomsPriceAndType(
            [
            'start_date' => '2017-12-01',
            'end_date' => '2017-12-05',
            'days' => '1',
            'room_type_id' => 1,
            'available_rooms' => 1,
            'price' => 1500,
            'slug' => 'anit',
            'filtered_days' => '1'
            ],
            \App::make('App\LogicValidators\DataValidator')
        );

        $this->assertEquals($result->getStatusCode(), 204,
            $result->getData());
    }

}
