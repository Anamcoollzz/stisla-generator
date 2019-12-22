<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Divisi;

class DivisiController extends Controller
{
	public function index()
	{
		// mengambil data dari basisdata
		$data = Models\Divisi::all();
		// mempersiapkan view data
		return view('divisi.index', [
			'active'		=> 'contoh.index',
			'title'			=> 'Divisi',
			'data'			=> $data,
		]);
	}

	public function create()
	{
		// form action untuk simpan data
		$action = route('contoh.store');
		// mempersiapkan view form tambah
		return view('divisi.tambah', [
			'active'		=> 'contoh.index',
			'title'			=> 'Divisi Baru',
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
		Models\Divisi::create($data);

		// mengalihkan ke halaman sebelumnya dengan pesan
		return redirect()->back()->with('success_msg', 'Divisi berhasil ditambahkan');
	}

	public function edit(Models\Divisi $divisi)
	{
		// form action untuk simpan data
		$action = route('contoh.update', $divisi->id);
		// mempersiapkan view form ubah
		return view('divisi.tambah', [
			'active'        => 'contoh.index',
			'title'         => 'Ubah Divisi',
			'action'        => $action,
			'd'             => $divisi,
		]);
	}

	public function update(Request $request, Models\Divisi $divisi)
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
		$divisi->update($data);

		// mengalihkan ke halaman sebelumnya dengan pesan
		return redirect()->back()->with('success_msg', 'Divisi berhasil diperbarui');
	}

	public function destroy(Models\Divisi $divisi)
	{
		// menghapus data dari basisdata
		$divisi->delete();
		// mengalihkan ke halaman sebelumnya dengan pesan
		return redirect()->back()->with('success_msg', 'Divisi berhasil dihapus');
	}

}
