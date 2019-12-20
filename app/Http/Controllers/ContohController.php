<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contoh;

class ContohController extends Controller
{
	public function index()
	{
		// mengambil data dari basisdata
		$data = Contoh::all();
		// mempersiapkan view data
		return view('contoh.index', [
			'active'		=> 'contoh.index',
			'title'			=> 'Contoh',
			'data'			=> $data,
		]);
	}

	public function create()
	{
		// form action untuk simpan data
		$action = route('contoh.store');
		// mempersiapkan view form tambah
		return view('contoh.tambah', [
			'active'		=> 'contoh.index',
			'title'			=> 'Contoh Baru',
			'action'        => $action,
		]);
	}

	public function store(Request $request)
	{
		// validating every form input
		validasi

		// mempersiapkan data untuk disimpan ke basis data
		data;

		// menyimpan ke basisdata
		Contoh::create($data);

		// mengalihkan ke halaman sebelumnya dengan pesan
		return redirect()->back()->with('success_msg', 'Contoh berhasil ditambahkan');
	}

	public function edit(Contoh $contoh)
	{
		// form action untuk simpan data
		$action = route('contoh.update', $contoh->id);
		// mempersiapkan view form ubah
		return view('contoh.tambah', [
			'active'        => 'contoh.index',
			'title'         => 'Ubah Contoh',
			'action'        => $action,
			'd'             => $contoh,
		]);
	}

	public function update(Request $request, Contoh $contoh)
	{
		// validating every form input
		validasi

		// mempersiapkan data untuk disimpan ke basis data
		data;

		// menyimpan ke basisdata
		$contoh->update($data);

		// mengalihkan ke halaman sebelumnya dengan pesan
		return redirect()->back()->with('success_msg', 'Contoh berhasil diperbarui');
	}

	public function destroy(Contoh $contoh)
	{
		// menghapus data dari basisdata
		$contoh->delete();
		// mengalihkan ke halaman sebelumnya dengan pesan
		return redirect()->back()->with('success_msg', 'Contoh berhasil dihapus');
	}

}
