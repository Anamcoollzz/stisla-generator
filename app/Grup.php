<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grup extends Model
{
    protected $table = 'grup';
    protected $fillable = ['nama', 'modul_id', 'kolom_bootstrap', 'sembunyikan'];
    protected $casts = [
        'sembunyikan'   => 'boolean',
    ];
    public $timestamps = false;
    public function modul()
    {
    	return $this->belongsTo('App\Modul', 'modul_id');
    }
    public function kolom()
    {
    	return $this->hasMany('App\Kolom', 'grup_id');
    }
}
