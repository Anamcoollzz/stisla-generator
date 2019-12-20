<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modul;
use App\Kolom;

class HomeController extends Controller
{

    private $PRIMARY_TYPE = [
        'bigIncrements',
        'increments',
        'tinyIncrements',
    ];

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
        $modul->load('projek', 'kolom');
        return view('home',['modul'=>$modul, 'active'=>'projek']);
    }

    public function generator(Modul $modul, Request $request)
    {
        $this->simpanKonfigurasi($modul, $request);
        // return $request->all();
        $this->generateController($request);
        $this->generateModel($request);
        $this->generateMigration($request);
        return 'sukses';
    }

    private function simpanKonfigurasi($modul, $request)
    {
        $modul->update([
            'nama'              => $request->modul,
            'controller'        => $request->controller,
            'model'             => $request->model,
            'views'             => $request->views,
            'tabel'             => $request->tabel,
            'migration'         => $request->migration,
            'seeder'            => $request->seeder,
            'views'             => $request->views,
        ]);
        Kolom::where('modul_id', $modul->id)->delete();
        foreach ($request->kolom as $kolom) {
            Kolom::create([
                'nama'      => $kolom['nama'],
                'tipe'      => $kolom['tipe'],
                'unik'      => $kolom['unique'],
                'rules'     => $kolom['rules'],
                'modul_id'  => $modul->id,
            ]);
        }
    }

    private function generateController($request)
    {
        $controller_content = file_get_contents(base_path('app/Http/Controllers/ContohController.php'));
        // penamaan controller
        $controller_content = str_replace("Contoh", $request->modul, $controller_content);
        // penamaan folder views
        $controller_content = str_replace('contoh', $request->views, $controller_content);
        // validasi kolom
        $validasi = "\$request->validate([\n";
        $data = "\$data = [\n";
        foreach ($request->kolom as $kolom) {
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
        $controller_path = $request->path.'\app\Http\Controllers\\'.$request->controller.'.php';
        $controller_file = fopen($controller_path, "wa+") or die("Unable to open file!");
        fwrite($controller_file, $controller_content);
        fclose($controller_file);
    }

    private function generateModel($request)
    {
        $model_content = file_get_contents(base_path('app/Contoh.php'));
        // penamaan model
        $model_content = str_replace("Contoh", $request->model, $model_content);
        // penamaan tabel di basisdata
        $model_content = str_replace('NAMA_TABEL', $request->tabel, $model_content);
        // fillable in model
        $fillable = '';
        foreach ($request->kolom as $kolom) {
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
        foreach ($request->kolom as $column) {
            if(!in_array($column['tipe'], $this->PRIMARY_TYPE))
                $kolom .= "\n\t\t\t\$table->{$column['tipe']}('{$column['nama']}');";
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

    private function dashesToCamelCase($string, $capitalizeFirstCharacter = true) 
    {

        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }
}
