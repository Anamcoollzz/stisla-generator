<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\KONTOL;

class DivifsdsifdController extends Controller
{
	public function index()
	{
		// mengambil data dari basisdata
		$data = KONTOL::all();
		// mempersiapkan view data
		return view('jijik.index', [
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
		return view('jijik.tambah', [
			'active'		=> 'contoh.index',
			'title'			=> 'Contoh Baru',
			'action'        => $action,
		]);
	}

	public function store(Request $request)
	{
		// validating every form input
		$request->validate([
			'nama' => ["jhgjhj",9],
			'das' => [],
		]);

		// mempersiapkan data untuk disimpan ke basis data
		$data = [
			'nama' => $request->nama,
			'das' => $request->das,
		];

		// menyimpan ke basisdata
		KONTOL::create($data);

		// mengalihkan ke halaman sebelumnya dengan pesan
		return redirect()->back()->with('success_msg', 'Contoh berhasil ditambahkan');
	}

	public function edit(KONTOL $kontol)
	{
		// form action untuk simpan data
		$action = route('contoh.update', $kontol->id);
		// mempersiapkan view form ubah
		return view('jijik.tambah', [
			'active'        => 'contoh.index',
			'title'         => 'Ubah Contoh',
			'action'        => $action,
			'd'             => $kontol,
		]);
	}

	public function update(Request $request, KONTOL $kontol)
	{
		// validating every form input
		$request->validate([
			'nama' => ["jhgjhj",9],
			'das' => [],
		]);

		// mempersiapkan data untuk disimpan ke basis data
		$data = [
			'nama' => $request->nama,
			'das' => $request->das,
		];

		// menyimpan ke basisdata
		$kontol->update($data);

		// mengalihkan ke halaman sebelumnya dengan pesan
		return redirect()->back()->with('success_msg', 'Contoh berhasil diperbarui');
	}

	public function destroy(KONTOL $kontol)
	{
		// menghapus data dari basisdata
		$kontol->delete();
		// mengalihkan ke halaman sebelumnya dengan pesan
		return redirect()->back()->with('success_msg', 'Contoh berhasil dihapus');
	}

}
