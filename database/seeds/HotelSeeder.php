<?php
use Illuminate\Database\Seeder;
use App\Models\Hotel;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hotels = [
            [
                'name' => 'Kathmandu Hotel',
                'slug' => 'kathmandu-hotel',
                'about' => 'Details about Kathmandu Hotel',
            ],
            [
                'name' => 'Butwal Hotel',
                'slug' => 'butwal-hotel',
                'about' => 'Details about Butwal Hotel',
            ],
            [
                'name' => 'Soltee Hotel',
                'slug' => 'soltee-hotel',
                'about' => 'Details about Soltee Hotel',
            ]
        ];

        foreach ($hotels as $hotel) {
            Hotel::create([
                'name' => $hotel['name'],
                'slug' => $hotel['slug'],
                'about' => $hotel['about'],
                'created_at' => (new DateTime)->format('Y-m-d H:i:s'),
                'updated_at' => (new DateTime)->format('Y-m-d H:i:s')
            ]);
        }
    }
}
