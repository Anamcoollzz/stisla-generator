<?php

use Illuminate\Database\Seeder;
use Faker\Factory;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create('id_ID');
        $jumlah_dummy = 100;
        $data = [];
        $waktu_skrg = date('Y-m-d H:i:s');
        foreach (range(1,$jumlah_dummy) as $i) {
        	$data[] = [
				'kkkk' => '',
				'created_at' => $waktu_skrg,
				'updated_at' => $waktu_skrg,
        	];
        }
        DB::table('dadasd')->insert($data);
    }
}
