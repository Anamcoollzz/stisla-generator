<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    protected $table = 'modul';
    protected $fillable = ['nama', 'projek_id', 'controller', 'model', 'views', 'migration', 'tabel', 'seeder'];
    public $timestamps = false;
    public function projek()
    {
    	return $this->belongsTo('App\Projek', 'projek_id');
    }
    public function kolom()
    {
    	return $this->hasMany('App\Kolom', 'modul_id');
    }
}
