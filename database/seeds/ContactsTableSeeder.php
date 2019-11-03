<?php

use App\Contact;
use League\Csv\Reader;
use Illuminate\Database\Seeder;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = Reader::createFromPath('./database/seeds/data/contacts.csv', 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        foreach($records as $record) {
            Contact::firstOrCreate(
                [
                    'phone' => $record['phone'],
                ],
                [
                    'name' => $record['name'],
                    'phone' => $record['phone'],
                    'email' => $record['email'],
                ]
            );
        }
    }
}
