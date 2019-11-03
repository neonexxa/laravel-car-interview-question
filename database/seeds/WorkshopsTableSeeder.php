<?php

use App\Workshop;
use League\Csv\Reader;
use Illuminate\Database\Seeder;

class WorkshopsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = Reader::createFromPath('./database/seeds/data/workshops.csv', 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        foreach($records as $record) {
            Workshop::firstOrCreate(
                [
                    'phone' => $record['phone'],
                ],
                [
                    'name' => $record['name'],
                    'phone' => $record['phone'],
                    'latitude' => $record['latitude'],
                    'longitude' => $record['longitude'],
                    'opening_time' => $record['opening_time'],
                    'closing_time' => $record['closing_time'],
                ]
            );
        }
    }
}
