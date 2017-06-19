<?php
use Illuminate\Database\Seeder;
use App\Models\HotelRoomType;

class HotelRoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hotelTypes = [
            [
                'name' => 'Double room',
                'slug' => 'double-room'
            ],
            [
                'name' => 'Single room',
                'slug' => 'single-room'
            ]
        ];

        foreach ($hotelTypes as $type) {
            HotelRoomType::create([
                'name' => $type['name'],
                'slug' => $type['slug'],
                'created_at' => (new DateTime)->format('Y-m-d H:i:s'),
                'updated_at' => (new DateTime)->format('Y-m-d H:i:s')
            ]);
        }
    }
}
