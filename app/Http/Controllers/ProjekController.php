<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Projek;
use App\Modul;

class ProjekController extends Controller
{
    public function projek()
    {
    	$data = Projek::all();
    	return view('projek', ['data'=>$data, 'active'=>'projek']);
    }

    public function tambah(Request $request)
    {
    	$request->validate([
    		'nama'		=> 'required',
    	]);
    	Projek::create([
    		'nama'		=> $request->nama,
    	]);
    	return redirect()->back()->with('status', 'Projek berhasil ditambahkan');
    }

    public function hapus(Projek $projek)
    {
    	$projek->delete();
    	return redirect()->back()->with('status', 'Projek berhasil dihapus');
    }

    public function modul(Projek $projek)
    {
    	$data = $projek->modul()->get();
    	return view('modul', ['data'=>$data, 'active'=>'projek', 'projek_id'=>$projek->id, 'projek'=>$projek]);
    }

    public function modulTambah(Projek $projek, Request $request)
    {
    	$request->validate([
    		'nama'		=> 'required',
    	]);
    	Modul::create([
    		'nama'			=> $request->nama,
    		'projek_id'		=> $projek->id,
    	]);
    	return redirect()->back()->with('status', 'Modul berhasil ditambahkan');
    }

    public function modulHapus(Modul $modul)
    {
    	$modul->delete();
    	return redirect()->back()->with('status', 'Modul berhasil dihapus');
    }
}
