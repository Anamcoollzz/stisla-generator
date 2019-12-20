<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kolom extends Model
{
    protected $table = 'kolom';
    protected $fillable = ['nama', 'modul_id', 'nama_asli', 'tipe', 'panjang', 'unik', 'rules'];
    public $timestamps = false;
    protected $appends = [
        'unique',
    ];
    protected $casts = [
        'rules' => 'array',
    ];
    public function modul()
    {
    	return $this->belongsTo('App\Modul', 'modul_id');
    }
    public function getUniqueAttribute()
    {
        return $this->unik == 1;
    }
}
