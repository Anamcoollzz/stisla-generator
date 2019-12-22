<?php

namespace App\Http\Controllers\NAMESPACE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\NAMA_MODEL;

class NAMA_CONTROLLER extends Controller
{
	public function index()
	{
		// mengambil data dari basisdata
		$data = NAMA_MODEL::all();
		// mempersiapkan view data
		return view('FOLDER_VIEWS.index', [
			'active'		=> 'ROUTE.index',
			'title'			=> 'NAMA_MODUL',
			'data'			=> $data,
		]);
	}

	public function create()
	{
		// form action untuk simpan data
		$action = route('ROUTE.store');
		// mempersiapkan view form tambah
		return view('FOLDER_VIEWS.tambah', [
			'active'		=> 'ROUTE.index',
			'title'			=> 'NAMA_MODUL Baru',
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
		NAMA_MODEL::create($data);

		// mengalihkan ke halaman sebelumnya dengan pesan
		return redirect()->back()->with('success_msg', 'NAMA_MODUL berhasil ditambahkan');
	}

	public function edit(NAMA_MODEL $NAMA_MODEL)
	{
		// form action untuk simpan data
		$action = route('ROUTE.update', $NAMA_MODEL->id);
		// mempersiapkan view form ubah
		return view('FOLDER_VIEWS.tambah', [
			'active'        => 'ROUTE.index',
			'title'         => 'Ubah NAMA_MODUL',
			'action'        => $action,
			'd'             => $NAMA_MODEL,
		]);
	}

	public function update(Request $request, NAMA_MODEL $NAMA_MODEL)
	{
		// validating every form input
		validasi

		// mempersiapkan data untuk disimpan ke basis data
		data;

		// menyimpan ke basisdata
		$NAMA_MODEL->update($data);

		// mengalihkan ke halaman sebelumnya dengan pesan
		return redirect()->back()->with('success_msg', 'NAMA_MODUL berhasil diperbarui');
	}

	public function destroy(NAMA_MODEL $NAMA_MODEL)
	{
		// menghapus data dari basisdata
		$NAMA_MODEL->delete();
		// mengalihkan ke halaman sebelumnya dengan pesan
		return redirect()->back()->with('success_msg', 'NAMA_MODUL berhasil dihapus');
	}

}
