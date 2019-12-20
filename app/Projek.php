<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Projek extends Model
{
    protected $table = 'projek';
    protected $fillable = ['nama', 'path'];
    public $timestamps = false;
    public function modul()
    {
    	return $this->hasMany('App\Modul', 'projek_id');
    }
}
