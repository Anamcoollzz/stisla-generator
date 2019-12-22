<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KONTOL extends Model
{

	// nama tabel di basisdata
    protected $table = 'divisi';

    // kolom yang boleh diisi
    protected $fillable = [
		'nama',
		'das',
    ];

    // timestamp created_at dan updated_at
    public $timestamps = true;

}
