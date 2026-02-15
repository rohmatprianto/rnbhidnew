<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\EventPhoto;
use App\Models\EventPhotoOrder;

class EventPhotoOrderSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada event dulu
        if (EventPhoto::count() === 0) {
            EventPhoto::insert([
                [
                    'title' => 'Pushbike Race',
                    'location' => 'Stadion',
                    'event_date' => now()->addDays(3)->toDateString(),
                    'status' => 'open',
                    'cover_path' => null,
                    'price_notes' => '2 Moto / Kualifikasi Rp.50.000; Lebih dari 2 Moto Rp.70.000',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Fun Race',
                    'location' => 'Lapangan',
                    'event_date' => now()->addDays(10)->toDateString(),
                    'status' => 'open',
                    'cover_path' => null,
                    'price_notes' => 'Paket Basic Rp.50.000; Paket Pro Rp.100.000',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        $eventIds = EventPhoto::pluck('id')->all();

        // Opsional: kosongkan dulu biar repeatable (hapus kalau tidak mau)
        // EventPhotoOrder::query()->delete();

        $classes = ['Boys', 'Girls', 'FFA', 'Open Mini'];
        $years   = ['2023','2022','2021','2020','2019','2018','Other'];

        // Generate 50 orders
        for ($i = 0; $i < 50; $i++) {
            $class = $classes[array_rand($classes)];
            $year  = $years[array_rand($years)];
            $category = $class . ' ' . $year;

            $guardian = fake()->name();
            $riderFull = fake()->name();
            $riderNick = Str::of($riderFull)->explode(' ')->first();

            EventPhotoOrder::create([
                'event_photo_id'   => $eventIds[array_rand($eventIds)],
                'guardian_name'    => $guardian,
                'email'            => fake()->safeEmail(),
                'phone'            => fake()->numerify('08##########'),
                'rider_full_name'  => $riderFull,
                'rider_nickname'   => (string) $riderNick,
                'category'         => $category,
                'plate_no'     => fake()->boolean(60) ? fake()->bothify('###-??') : null,
                'batch'            => fake()->boolean(60) ? (string) fake()->numberBetween(1, 8) : null,
                'instagram'        => fake()->boolean(70) ? '@' . fake()->userName() : null,
                'created_at'       => now()->subDays(fake()->numberBetween(0, 30)),
                'updated_at'       => now(),
            ]);
        }
    }
}
