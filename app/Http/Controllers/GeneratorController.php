<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Projek;
use App\Menu;

class GeneratorController extends Controller
{
	public function menu(Projek $projek, Request $request)
	{
		// menghapus dari basisdata
		Menu::where('projek_id', $projek->id)->delete();
		$seeder_content = file_get_contents(base_path('database/seeds/ContohGenerateMenu.php'));
        // kolom in seeder
		$menu_seeder = '';
		foreach ($request->menu as $menu) {
			$menu_seeder .= "\n\t\t\$menu = Menu::updateOrCreate([";
			$menu_seeder .= "\n\t\t\t'id'		\t=> \$i++,";
			$menu_seeder .= "\n\t\t], [";
			$menu_seeder .= "\n\t\t\t'nama'		\t=> '{$menu['nama']}',";
			$menu_seeder .= "\n\t\t\t'ikon'		\t=> '{$menu['ikon']}',";
			$menu_seeder .= "\n\t\t\t'route'		\t=> '{$menu['route']}',";
			$menu_seeder .= "\n\t\t\t'is_blank'		=> '{$menu['is_blank']}',";
			$menu_seeder .= "\n\t\t]);";
			// menyimpan ke basisdata
			$menuDb = Menu::create([
				'nama'			=> $menu['nama'],
				'ikon'			=> $menu['ikon'],
				'route'			=> $menu['route'],
				'is_blank'		=> $menu['is_blank'],
				'projek_id'		=> $projek->id,
			]);
			if(count($menu['sub']) > 0){
				foreach ($menu['sub'] as $sub) {
					Menu::create([
						'nama'			=> $sub['nama'],
						'ikon'			=> $sub['ikon'],
						'route'			=> $sub['route'],
						'is_blank'		=> $sub['is_blank'],
						'projek_id'		=> $projek->id,
						'parent_id'		=> $menuDb->id,
					]);
					$menu_seeder .= "\n\t\t// sub dari ".$menu['nama'];
					$menu_seeder .= "\n\t\tMenu::updateOrCreate([";
					$menu_seeder .= "\n\t\t\t'id'		\t=> \$i++,";
					$menu_seeder .= "\n\t\t], [";
					$menu_seeder .= "\n\t\t\t'nama'		\t=> '{$sub['nama']}',";
					$menu_seeder .= "\n\t\t\t'ikon'		\t=> '{$sub['ikon']}',";
					$menu_seeder .= "\n\t\t\t'route'		\t=> '{$sub['route']}',";
					$menu_seeder .= "\n\t\t\t'is_blank'		=> '{$sub['is_blank']}',";
					$menu_seeder .= "\n\t\t\t'parent_id'		=> \$menu->id,";
					$menu_seeder .= "\n\t\t]);";
				}
			}
			$menu_seeder .= "\n";
		}
		$seeder_content = str_replace('MENU', $menu_seeder, $seeder_content);
		$seeder_path = $request->path.'\database\seeds\GenerateMenu.php';
		$seeder_file = fopen($seeder_path, "wa+") or die("Unable to open file!");
		fwrite($seeder_file, $seeder_content);
		fclose($seeder_file);
		return 'sukses';
	}
}
