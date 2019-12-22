<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{

	// nama tabel di basisdata
    protected $table = 'dadasd';

    // kolom yang boleh diisi
    protected $fillable = [
		'kkkk',
    ];

    // timestamp created_at dan updated_at
    public $timestamps = true;

}
