<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modul;
use App\Kolom;
use App\Grup;

class HomeController extends Controller
{

    private $PRIMARY_TYPE = [
        'bigIncrements',
        'increments',
        'tinyIncrements',
    ];

    private $arrayKolom = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Modul $modul)
    {
        $modul->load(['projek', 'kolom'=>function($q){
            $q->whereNull('grup_id')->distinct('nama');
        }, 'grup.kolom']);
        $modul_lainnya = Modul::with(['kolom'=>function($q){
            $q->distinct('nama');
        }])->where('id', '!=', $modul->id)->where('projek_id', $modul->projek_id)->get();
        // return $modul_lainnya;
        return view('home',['modul'=>$modul, 'active'=>'projek', 'modul_lainnya'=>$modul_lainnya]);
    }

    public function generator(Modul $modul, Request $request)
    {
        $this->arrayKolom = array_merge($this->arrayKolom, $request->kolom);
        foreach ($request->grup as $grup) {
            $this->arrayKolom = array_merge($this->arrayKolom, $grup['kolom']);
        }
        $this->simpanKonfigurasi($modul, $request);
        // return $request->all();
        if($request->crud){
            $this->generateController($request);
            $this->generateModel($request);
            $this->generateViews($request);
            $this->generateMigration($request);
            $this->generateSeeder($request);
        }else{
            $this->generateControllerOnly($request);
        }
        return 'sukses';
    }

    private function simpanKonfigurasi($modul, $request)
    {
        \DB::transaction(function() use ($modul, $request){
            // memperbarui modul
            $modul->update([
                'nama'                  => $request->modul,
                'ikon'                  => $request->ikon,
                'controller'            => $request->controller,
                'model'                 => $request->model,
                'views'                 => $request->views,
                'tabel'                 => $request->tabel,
                'migration'             => $request->migration,
                'seeder'                => $request->seeder,
                'views'                 => $request->views,
                'namespace_model'       => $request->namespace_model,
                'namespace_controller'  => $request->namespace_controller,
            ]);
            Kolom::where('modul_id', $modul->id)->delete();
            Grup::where('modul_id', $modul->id)->delete();
            foreach ($request->kolom as $kolom) {
                Kolom::create([
                    'nama'              => $kolom['nama'],
                    'nama_asli'         => $kolom['nama_asli'],
                    'tipe'              => $kolom['tipe'],
                    'unik'              => $kolom['unique'],
                    'nullable'          => isset($kolom['nullable']) ? $kolom['nullable'] : null,
                    'rules'             => $kolom['rules'],
                    'kolom_bootstrap'   => $kolom['kolom_bootstrap'],
                    'min'               => isset($kolom['min']) ? $kolom['min'] : null,
                    'max'               => isset($kolom['max']) ? $kolom['max'] : null,
                    'sembunyikan'       => $kolom['sembunyikan'],
                    'faker'             => isset($kolom['faker']) ? $kolom['faker'] : '',
                    'ikon'              => isset($kolom['ikon']) ? $kolom['ikon'] : '',
                    'tabel_kolom'       => $kolom['tabel_kolom'],
                    'modul_id'          => $modul->id,
                    'jenis_form'        => isset($kolom['jenis_form']) ? $kolom['jenis_form'] : '',
                    'modul_parent'      => $kolom['modul_parent'],
                    'kolom_parent'      => $kolom['kolom_parent'],
                    'kolom_view'        => $kolom['kolom_view'],
                ]);
            }
            foreach ($request->grup as $grup) {
                $g = Grup::create([
                    'nama'              => $grup['nama'],
                    'kolom_bootstrap'   => $grup['kolom_bootstrap'],
                    'sembunyikan'       => $grup['sembunyikan'],
                    'modul_id'          => $modul->id,
                ]);
                foreach ($grup['kolom'] as $kolom) {
                    Kolom::create([
                        'nama'              => $kolom['nama'],
                        'nama_asli'         => $kolom['nama_asli'],
                        'tipe'              => $kolom['tipe'],
                        'unik'              => $kolom['unique'],
                        'nullable'          => isset($kolom['nullable']) ? $kolom['nullable'] : null,
                        'rules'             => $kolom['rules'],
                        'kolom_bootstrap'   => $kolom['kolom_bootstrap'],
                        'min'               => isset($kolom['min']) ? $kolom['min'] : null,
                        'max'               => isset($kolom['max']) ? $kolom['max'] : null,
                        'sembunyikan'       => $kolom['sembunyikan'],
                        'faker'             => isset($kolom['faker']) ? $kolom['faker'] : '',
                        'ikon'              => isset($kolom['ikon']) ? $kolom['ikon'] : '',
                        'tabel_kolom'       => $kolom['tabel_kolom'],
                        'modul_id'          => $modul->id,
                        'grup_id'           => $g->id,
                        'jenis_form'        => isset($kolom['jenis_form']) ? $kolom['jenis_form'] : '',
                        'modul_parent'      => $kolom['modul_parent'],
                        'kolom_parent'      => $kolom['kolom_parent'],
                        'kolom_view'        => $kolom['kolom_view'],
                    ]);
                }
            }
            // memperbarui projek
            $modul->projek()->update([
                'path'              => $request->path,
            ]);
        });
    }

    private function generateController($request)
    {
        $contoh_controller = base_path('app/Http/Controllers/ContohController.php');
        if($request->namespace_controller){
            $contoh_controller = base_path('app/Http/Controllers/Contoh2Controller.php');
        }
        $controller_content = file_get_contents($contoh_controller);
        if($request->namespace_controller){
            $controller_content = str_replace("NAMESPACE", $request->namespace_controller, $controller_content);
        }
        // penamaan controller
        $controller_content = str_replace("NAMA_CONTROLLER", $request->controller, $controller_content);
        // penamaan modul
        $controller_content = str_replace("NAMA_MODUL", $request->modul, $controller_content);
        // penamaan route
        $controller_content = str_replace("ROUTE", $request->tabel, $controller_content);
        // penamaan folder views
        if($request->namespace_views){
            $controller_content = str_replace('FOLDER_VIEWS', $request->namespace_views.'.'.$request->views, $controller_content);
        }else{
            $controller_content = str_replace('FOLDER_VIEWS', $request->views, $controller_content);
        }
        // penamaan model di controller
        $controller_content = str_replace('$NAMA_MODEL', '$'.strtolower($request->model), $controller_content);
        $nama_model = $request->model;
        if($request->namespace_model){
            $nama_model = $request->namespace_model.'\\'.$request->model;
        }
        $controller_content = str_replace('NAMA_MODEL', $nama_model, $controller_content);
        // validasi kolom
        $validasi = "\$request->validate([\n";
        $data = "\$data = [\n";
        foreach ($this->arrayKolom as $kolom) {
            if(!in_array($kolom['tipe'], $this->PRIMARY_TYPE)){
                $validasi .= "\t\t\t'{$kolom['nama']}' => VALIDASI,\n";
                $validasi = str_replace('VALIDASI', json_encode($kolom['rules']), $validasi);
                $data .= "\t\t\t'{$kolom['nama']}' => \$request->{$kolom['nama']},\n";
            }
        }
        $validasi .= "\t\t]);";
        $data .= "\t\t];";
        $controller_content = str_replace('validasi', $validasi, $controller_content);
        // mempersiapkan kolom untuk disimpan ke database
        $controller_content = str_replace('data;', $data, $controller_content);
        // simpan file
        $controller_path = $request->path.'\app\Http\Controllers\\';
        if($request->namespace_controller){
            $controller_path = $controller_path.$request->namespace_controller;
            $folderAda = file_exists($controller_path);
            if(!$folderAda){
                mkdir($controller_path);
            }
            $controller_path = $controller_path.'\\';
        }
        $controller_path = $controller_path.$request->controller.'.php';
        $controller_file = fopen($controller_path, "wa+") or die("Unable to open file!");
        fwrite($controller_file, $controller_content);
        fclose($controller_file);
    }

    private function generateControllerOnly($request)
    {
        $contoh_controller = base_path('app/Http/Controllers/Contoh3Controller.php');
        $controller_content = file_get_contents($contoh_controller);
        if($request->namespace_controller){
            $controller_content = str_replace("NAMESPACE", '\\'.$request->namespace_controller, $controller_content);
        }else{
            $controller_content = str_replace("NAMESPACE", '', $controller_content);
        }
        // penamaan controller
        $controller_content = str_replace("NAMA_CONTROLLER", $request->controller, $controller_content);
        // simpan file
        $controller_path = $request->path.'\app\Http\Controllers\\';
        if($request->namespace_controller){
            $controller_path = $controller_path.$request->namespace_controller;
            $folderAda = file_exists($controller_path);
            if(!$folderAda){
                mkdir($controller_path);
            }
            $controller_path = $controller_path.'\\';
        }
        $controller_path = $controller_path.$request->controller.'.php';
        $controller_file = fopen($controller_path, "wa+") or die("Unable to open file!");
        fwrite($controller_file, $controller_content);
        fclose($controller_file);
    }

    private function generateModel($request)
    {
        $model_content = file_get_contents(base_path('app/Contoh.php'));
        // penamaan model
        $model_content = str_replace("NAMA_MODEL", $request->model, $model_content);
        // penamaan tabel di basisdata
        $model_content = str_replace('NAMA_TABEL', $request->tabel, $model_content);
        // penamaan namespace
        if($request->namespace_model)
            $model_content = str_replace('NAMESPACE_MODEL', '\\'.$request->namespace_model, $model_content);
        else
            $model_content = str_replace('NAMESPACE_MODEL', '', $model_content);
        // fillable in model
        $fillable = '';
        foreach ($this->arrayKolom as $kolom) {
            if(!in_array($kolom['tipe'], $this->PRIMARY_TYPE))
                $fillable .= "\n\t\t'{$kolom['nama']}',";
        }
        $model_content = str_replace('FILLABLE', $fillable, $model_content);
        // timestamps in model
        $model_content = str_replace('TIMESTAMP', $request->timestamps ? 'true' : 'false', $model_content);
        // simpan file
        $model_path = $request->path.'\app\\'.$request->model.'.php';
        $model_file = fopen($model_path, "wa+") or die("Unable to open file!");
        fwrite($model_file, $model_content);
        fclose($model_file);
    }

    private function generateViews($request)
    {
        // cek folder
        $views_path = $request->namespace_views ? $request->namespace_views.'.'.$request->views : $request->views;
        $path_array = explode('.', $views_path);
        $folderTemp = '';
        foreach ($path_array as $folder) {
            $folderTemp .= '\\'.$folder;
            $views_path = $request->path.'\\resources\\views'.$folderTemp;
            $path_ada = file_exists($views_path);
            if(!$path_ada){
                mkdir($views_path);
            }
        }
        # INDEX
        $index_content = file_get_contents(base_path('resources/views/contoh/index.blade.php'));
        // penamaan route
        $index_content = str_replace("ROUTE", $request->tabel, $index_content);
        // set ikon
        if($request->ikon)
            $index_content = str_replace("IKON", '<i class="'.$request->ikon.'"></i>', $index_content);
        else
            $index_content = str_replace("IKON", '', $index_content);
        // set tabel
        $kolom_head_tabel = '';
        $kolom_body_tabel = '';
        foreach ($request->kolom as $kolom) {
            if(!in_array($kolom['tipe'], $this->PRIMARY_TYPE)){
                $kolom_head_tabel .= "\n\t\t\t\t\t\t\t\t\t<th>{$kolom['nama_asli']}</th>";
                $kolom_body_tabel .= "\n\t\t\t\t\t\t\t\t\t<th>{{ \$d->{$kolom['nama']} }}</th>";
            }
        }
        $index_content = str_replace("KOLOM_HEAD_TABEL", $kolom_head_tabel, $index_content);
        $index_content = str_replace("KOLOM_BODY_TABEL", $kolom_body_tabel, $index_content);
        $index_path = $views_path.'\\index.blade.php';
        $index_file = fopen($index_path, "wa+") or die("Unable to open file!");
        fwrite($index_file, $index_content);
        fclose($index_file);

        # TAMBAH
        $tambah_content = file_get_contents(base_path('resources/views/contoh/tambah.blade.php'));
        // penamaan route
        $tambah_content = str_replace("NAMA_ROUTE", $request->tabel, $tambah_content);
        // set ikon
        if($request->ikon)
            $tambah_content = str_replace("IKON", '<i class="'.$request->ikon.'"></i>', $tambah_content);
        else
            $tambah_content = str_replace("IKON", '', $tambah_content);
        // penamaan modul
        $tambah_content = str_replace("NAMA_MODUL", $request->modul, $tambah_content);
        $form = $this->setForm($request->kolom, $request);
        $tambah_content = str_replace("FORM", $form, $tambah_content);
        $kolomCollection = collect($request->kolom);
        if(in_array('date', $kolomCollection->pluck('tipe')->toArray())){
            $tambah_content = str_replace("IMPORT", "@include('stisla.import.daterangepicker')", $tambah_content);
        }else{
            $tambah_content = str_replace("IMPORT", "", $tambah_content);
        }
        $grup_lainnya = '';
        foreach ($request->grup as $grup) {
            $grup_lainnya .= "<div class=\"{$grup['kolom_bootstrap']}\">\n";
            $grup_lainnya .= "<div class=\"card\">\n";
            $grup_lainnya .= "<div class=\"card-header\">\n";
            $grup_lainnya .= "<h4>{$grup['nama']}</h4>\n";
            $grup_lainnya .= "</div>";
            $grup_lainnya .= "<div class=\"card-body\">";
            $grup_lainnya .= $this->setForm($grup['kolom'], $request);
            $grup_lainnya .= "</div>\n";
            $grup_lainnya .= "</div>\n";
            $grup_lainnya .= "</div>\n";
        }
        $tambah_content = str_replace("GRUP_LAINNYA", $grup_lainnya, $tambah_content);
        $tambah_path = $views_path.'\\tambah.blade.php';
        $tambah_file = fopen($tambah_path, "wa+") or die("Unable to open file!");
        fwrite($tambah_file, $tambah_content);
        fclose($tambah_file);
    }

    private function setForm($arrayKolom, $request)
    {
        $form = '';
        foreach ($arrayKolom as $kolom) {
            if(!in_array($kolom['tipe'], $this->PRIMARY_TYPE)){
                $form .= "\n<div class=\"{$kolom['kolom_bootstrap']}\">";
                $rules = $kolom['rules'];
                if(!is_array($rules)){
                    $rules = [];
                }
                $no_required = !in_array('required', $rules);
                if($no_required){
                    $no_required = 'true';
                }else{
                    $no_required = 'false';
                }
                // dd($no_required);
                $ikon = isset($kolom['ikon']) ? $kolom['ikon'] : '';
                $jenis_form = isset($kolom['jenis_form']) ? $kolom['jenis_form'] : '';
                if($kolom['tipe'] == 'date')
                    $jenis_form = 'datepicker';
                if($kolom['tipe'] == 'alamat')
                    $jenis_form = 'textarea';
                if($kolom['tipe'] != 'jenisKelamin'){
                    $foreignArray = [
                        'bigIntegerForeign', 'integerForeign', 'tinyIntegerForeign',
                    ];
                    if($jenis_form == 'inputnumber'){
                        $tambahan = '';
                        $tambahan .= $kolom['min'] ? "'min'=>".$kolom['min'] : '';
                        $tambahan .= $kolom['max'] ? "'max'=>".$kolom['min'] : '';
                        $form .= "\n@{$jenis_form}(['id'=>'{$kolom['nama']}', 'label'=>'{$kolom['nama_asli']}', 'ikon'=>'{$ikon}', 'value'=>isset(\$d)?\$d->{$kolom['nama']} : '', 'no_required'=>".$no_required.", {$tambahan}])";
                    }else if(in_array($kolom['tipe'], $foreignArray)){
                        $modulLainnya = Modul::find($kolom['modul_parent']);
                        if($modulLainnya->namespace_model)
                            $form .= "\n@php\n\$selectData = \App\\".$modulLainnya->namespace_model."\\".$modulLainnya->model;
                        else
                            $form .= "\n@php\n\$selectData = \App\\".$modulLainnya->model;
                        $form .= "::all();\n\$selectData = \$selectData->pluck('{$kolom['kolom_view']}', '{$kolom['kolom_parent']}');\n@endphp";
                        $form .= "\n@select(['id'=>'{$kolom['nama']}', 'label'=>'{$kolom['nama_asli']}', 'value'=>isset(\$d)?\$d->{$kolom['nama']} : '', 'no_required'=>".$no_required.", 'selectData'=>\$selectData])";
                    }else{
                        $form .= "\n@{$jenis_form}(['id'=>'{$kolom['nama']}', 'label'=>'{$kolom['nama_asli']}', 'ikon'=>'{$ikon}', 'value'=>isset(\$d)?\$d->{$kolom['nama']} : '', 'no_required'=>".$no_required."])";
                    }
                }
                else
                    $form .= "\n@select(['id'=>'{$kolom['nama']}', 'label'=>'{$kolom['nama_asli']}', 'selectData'=>['Laki-laki'=>'Laki-laki','Perempuan'=>'Perempuan',], 'value'=>isset(\$d)?\$d->{$kolom['nama']} : '', 'no_required'=>".$no_required."])";
                $form .= "\n</div>";
            }
        }
        return $form;
    }

    private function generateMigration($request)
    {
        $migration_content = file_get_contents(base_path('database/migrations/2019_12_19_090231_contoh.php'));
        // penamaan migration
        $nama_migration = explode('_', $request->migration);
        array_shift($nama_migration);
        array_shift($nama_migration);
        array_shift($nama_migration);
        array_shift($nama_migration);
        $nama_tabel = end($nama_migration);
        $nama_snake = implode('_', $nama_migration);
        $nama_migration = $this->dashesToCamelCase($nama_snake);
        $migration_content = str_replace("NAMA_MIGRATION", $nama_migration, $migration_content);
        // penamaan tabel di basisdata
        $migration_content = str_replace('NAMA_TABEL', $request->tabel, $migration_content);
        // cek berkas migration lama
        $berkasLama = glob($request->path.'/database/migrations/*');
        foreach ($berkasLama as $berkas) {
            if (strpos($berkas, $nama_snake) !== false) {
                unlink($berkas);
            }
        }
        // kolom in migration
        $kolom = '';
        foreach ($this->arrayKolom as $column) {
            $tipe = $column['tipe'];
            if(in_array($tipe, ['jenisKelamin', 'noHP', 'email'])){
                $tipe = 'string';
            }
            if($tipe == 'alamat'){
                $tipe = 'text';
            }
            $append = '';
            if(isset($column['nullable'])){
                if($column['nullable']){
                    $append .= "->nullable()";
                }
            }
            if($column['unique']){
                $append .= "->unique()";
            }
            if($tipe != 'string' && $tipe != 'text')
                $kolom .= "\n\t\t\t\$table->{$tipe}('{$column['nama']}'){$append};";
            else{
                $panjang = $column['panjang'] ? $column['panjang'] : 191;
                $kolom .= "\n\t\t\t\$table->{$tipe}('{$column['nama']}', {$panjang}){$append};";
            }
        }
        $migration_content = str_replace('KOLOM', $kolom, $migration_content);
        // timestamps in migration
        $migration_content = str_replace('TIMESTAMP', $request->timestamps ? "\n\t\t\t\$table->timestamps();" : '', $migration_content);
        // simpan file
        $migration_path = $request->path.'\database\migrations\\'.$request->migration.'.php';
        $migration_file = fopen($migration_path, "wa+") or die("Unable to open file!");
        fwrite($migration_file, $migration_content);
        fclose($migration_file);
    }

    private function generateSeeder($request)
    {
        $seeder_content = file_get_contents(base_path('database/seeds/ContohSeeder.php'));
        // penamaan seeder
        $nama_seeder = $request->seeder;
        $seeder_content = str_replace("NAMA_SEEDER", $nama_seeder, $seeder_content);
        // penamaan tabel
        $seeder_content = str_replace('NAMA_TABEL', $request->tabel, $seeder_content);
        // pembatasan jumlah
        $seeder_content = str_replace('JUMLAH', $request->jumlah_seeder, $seeder_content);
        // kolom in seeder
        $kolom = '';
        foreach ($this->arrayKolom as $column) {
            if(!in_array($column['tipe'], $this->PRIMARY_TYPE)){
                if(isset($column['faker'])){
                    if($column['faker'] == 'jenisKelamin'){
                        $kolom .= "\n\t\t\t\t'{$column['nama']}' => \$faker->randomElement(['Laki-laki','Perempuan']),";
                        continue;
                    }
                    if($column['faker'] == 'tanggalSekarang'){
                        $kolom .= "\n\t\t\t\t'{$column['nama']}' => date('Y-m-d'),";
                        continue;
                    }
                    if($column['faker'] == 'alamat'){
                        $kolom .= "\n\t\t\t\t'{$column['nama']}' => \$faker->address,";
                        continue;
                    }
                    if($column['unique'])
                        $kolom .= "\n\t\t\t\t'{$column['nama']}' => \$faker->unique()->{$column['faker']},";
                    else
                        $kolom .= "\n\t\t\t\t'{$column['nama']}' => \$faker->{$column['faker']},";
                }
                else
                    $kolom .= "\n\t\t\t\t'{$column['nama']}' => '',";
            }
        }
        if($request->timestamps){
            $kolom .= "\n\t\t\t\t'created_at' => \$waktu_skrg,";
            $kolom .= "\n\t\t\t\t'updated_at' => \$waktu_skrg,";
        }
        $seeder_content = str_replace('KOLOM', $kolom, $seeder_content);
        // simpan file
        $seeder_path = $request->path.'\database\seeds\\'.$request->seeder.'.php';
        $seeder_file = fopen($seeder_path, "wa+") or die("Unable to open file!");
        fwrite($seeder_file, $seeder_content);
        fclose($seeder_file);
    }

    private function dashesToCamelCase($string, $capitalizeFirstCharacter = true)
    {

        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }
}
