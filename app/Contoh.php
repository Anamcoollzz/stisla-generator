<?php

namespace AppNAMESPACE_MODEL;

use Illuminate\Database\Eloquent\Model;

class NAMA_MODEL extends Model
{

	// nama tabel di basisdata
    protected $table = 'NAMA_TABEL';

    // kolom yang boleh diisi
    protected $fillable = [FILLABLE
    ];

    // timestamp created_at dan updated_at
    public $timestamps = TIMESTAMP;

}
