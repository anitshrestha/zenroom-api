<?php

/**
 * Resources: 
 * https://scotch.io/tutorials/generate-dummy-laravel-data-with-model-factories
 */

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
    protected $faker;

    public function setUp()
    {
        parent::setUp();

        $this->hotelMock = m::mock(Hotel::class);
        $this->hotelRoomDetailMock = m::mock(HotelRoomDetail::class);
        $this->hotelRoomTypeMock = m::mock(HotelRoomType::class);
    
        $this->zenRoomRepository = new ZenRoomRepository(
            $this->hotelMock,
            $this->hotelRoomDetailMock,
            $this->hotelRoomTypeMock
        );

        $this->faker = \Faker\Factory::create();
    }

    /**
     * @covers :: createHotels
     */
    public function testCreateNewHotelsSuccessfully()
    {
        $this->zenRoomRepository = new ZenRoomRepository(
            new Hotel(),
            $this->hotelRoomDetailMock,
            $this->hotelRoomTypeMock
        );

        $fillable = [
            'name' => implode(' ', $this->faker->words(2)),
            'about' => $this->faker->sentence(2)
        ];

        $result = $this->zenRoomRepository->createHotel($fillable);
        
        $result  = json_decode($result);

        $this->assertEquals( 201, $result->status_code );
    }

    /**
     * @covers :: createHotelType
     */
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
            'name' => implode(' ', $this->faker->words(2)),
        ];

        $result = $this->zenRoomRepository->createHotelType($fillable);
        
        $result  = json_decode($result);

        $this->assertEquals($result->status_code, 201);
    }

    /**
     * @covers :: getHotelWithRoomsAndPrice
     */
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

        $this->assertEquals($result->getStatusCode(), 200);
    }

    /**
     * @covers :: findDatePeriod
     */
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

    /**
     *
     * @covers :: addHotelRoomsPriceAndType
     *
     */
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



    /**
     * Note: Work in progress
     * 
     * @covers ::addHotelRoomsPriceAndType
     */
    public function XtestInsertHotelRoomPriceAndTypeWithAvailableDateSuccessfully_WithValidData()
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
        $detail = new ZenRoomRepository(
            $this->hotelMock,
            $this->hotelRoomDetailMock,
            $this->hotelRoomTypeMock
         );

        $result = $detail->addHotelRoomsPriceAndType([
            'from_date' => '2016-12-01',
            'to_date' => '2016-12-12',
            'days' => ['Sunday' => 'Sundays'],
            'room_type_id' => 1,
            'available_rooms' => 1,
            'price' => 1500,
            'hotel_id' => 1,
            'slug' => 'test-slug'
        ]);

        $this->assertEquals($result->getStatusCode(), 204,
            $result->getData());
    }

}
