<?php

use App\Car;
use App\Contact;
use League\Csv\Reader;
use Illuminate\Database\Seeder;

class CarsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = Reader::createFromPath('./database/seeds/data/cars.csv', 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        foreach($records as $record) {
            $contact = Contact::where([
                'phone' => $record['contact_phone']
            ])->first();

            if(!$contact) {
                throw new Exception("Contact no found");
            }

            Car::firstOrCreate(
                [
                    'engine_number' => $record['engine_number'],
                    'chassis_number' => $record['chassis_number'],
                ],
                [
                    'contact_id' => $contact->id,
                    'make' => $record['make'],
                    'model' => $record['model'],
                    'engine_number' => $record['engine_number'],
                    'chassis_number' => $record['chassis_number'],
                    'latitude' => $record['latitude'],
                    'longitude' => $record['longitude'],
                ]
            );
        }
    }
}
