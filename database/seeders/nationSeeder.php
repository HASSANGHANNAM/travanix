<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\nation;

class nationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nations = [
            ['nation_name_in_english' => "Syria", 'nation_name_in_arabic' => "سوريا"]
        ];
        foreach ($nations as $nation) {
            nation::create($nation);
        }
    }
}
