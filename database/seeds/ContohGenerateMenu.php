<?php

use Illuminate\Database\Seeder;
use App\Menu;

class GenerateMenu extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Menu::truncate();
    	$i = 1;MENU
    }
}
