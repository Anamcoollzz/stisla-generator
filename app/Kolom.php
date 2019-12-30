<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kolom extends Model
{
    protected $table = 'kolom';
    protected $fillable = ['nama', 'modul_id', 'nama_asli', 'tipe', 'panjang', 'unik', 'rules', 'faker', 'ikon', 'tabel_kolom', 'kolom_bootstrap', 'sembunyikan', 'min', 'max', 'grup_id', 'nullable', 'jenis_form', 'modul_parent', 'kolom_parent', 'kolom_view', ];
    public $timestamps = false;
    protected $appends = [
        'unique',
    ];
    protected $casts = [
        'rules' => 'array',
        'sembunyikan'   => 'boolean',
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
