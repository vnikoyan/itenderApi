<?php

use Illuminate\Database\Seeder;

class PackagesStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $packages_state = [
            ['type' => "ONE_PERSON", 'quantity' => 5, 'price' => 29000, ],
            ['type' => "ONE_PERSON", 'quantity' => 10, 'price' => 41000, ],
            ['type' => "ONE_PERSON", 'quantity' => 15, 'price' => 53000, ],
            ['type' => "ONE_PERSON", 'quantity' => 20, 'price' => 65000, ],
            ['type' => "ONE_PERSON", 'quantity' => 25, 'price' => 65000, ],
            ['type' => "ONE_PERSON", 'quantity' => 30, 'price' => 89000, ],
            ['type' => "ONE_PERSON", 'quantity' => 30, 'price' => 89000, ],
            ['type' => "ONE_PERSON", 'quantity' => -1, 'price' => 99000, ],
        
            ['type' => "COMPETITIVE", 'quantity' => 5, 'price' => 29000, ],
            ['type' => "COMPETITIVE", 'quantity' => 10, 'price' => 41000, ],
            ['type' => "COMPETITIVE", 'quantity' => 15, 'price' => 53000, ],
            ['type' => "COMPETITIVE", 'quantity' => 20, 'price' => 65000, ],
            ['type' => "COMPETITIVE", 'quantity' => 25, 'price' => 65000, ],
            ['type' => "COMPETITIVE", 'quantity' => 30, 'price' => 89000, ],
            ['type' => "COMPETITIVE", 'quantity' => 30, 'price' => 89000, ],
            ['type' => "COMPETITIVE", 'quantity' => -1, 'price' => 99000, ]
        ];

        DB::table('packages_state')->insert($packages_state);
    }
}
