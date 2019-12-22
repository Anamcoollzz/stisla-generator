<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $fillable = ['nama', 'projek_id', 'ikon', 'route', 'is_blank', 'parent_id'];
    public $timestamps = false;
    public function projek()
    {
    	return $this->belongsTo('App\Projek', 'projek_id');
    }
    public function kolom()
    {
    	return $this->hasMany('App\Kolom', 'modul_id');
    }
    public function sub()
    {
        return $this->hasMany('App\Menu', 'parent_id');
    }
}
