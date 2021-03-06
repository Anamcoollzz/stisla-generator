<?php

Route::get('/', function () {
    return redirect()->route('projek');
});

Route::post('/generate/{modul}', 'HomeController@generator');
Route::post('/generate-menu/{projek}', 'GeneratorController@menu');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/projek', 'ProjekController@projek')->name('projek');
Route::post('/projek', 'ProjekController@tambah')->name('projek.tambah');
Route::get('/projek/hapus/{projek}', 'ProjekController@hapus')->name('projek.hapus');
Route::get('/modul/{projek}', 'ProjekController@modul')->name('modul');
Route::get('/menu/{projek}', 'ProjekController@menu')->name('menu');
Route::post('/modul/{projek}', 'ProjekController@modulTambah')->name('modul.tambah');
Route::get('/modul/hapus/{modul}', 'ProjekController@modulHapus')->name('modul.hapus');

Route::get('/generator/{modul}', 'HomeController@index')->name('generator');
