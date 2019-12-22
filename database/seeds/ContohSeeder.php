<?php

use Illuminate\Database\Seeder;
use Faker\Factory;

class NAMA_SEEDER extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('NAMA_TABEL')->truncate();
        $faker = Factory::create('id_ID');
        $jumlah_dummy = JUMLAH;
        $data = [];
        $waktu_skrg = date('Y-m-d H:i:s');
        foreach (range(1,$jumlah_dummy) as $i) {
        	$data[] = [KOLOM
        	];
        }
        DB::table('NAMA_TABEL')->insert($data);
    }
}
