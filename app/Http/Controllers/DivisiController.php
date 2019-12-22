<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Divisi;

class DivisiController extends Controller
{
	public function index()
	{
		// mengambil data dari basisdata
		$data = Divisi::all();
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
			'kkkk' => [],
		]);

		// mempersiapkan data untuk disimpan ke basis data
		$data = [
			'kkkk' => $request->kkkk,
		];

		// menyimpan ke basisdata
		Divisi::create($data);

		// mengalihkan ke halaman sebelumnya dengan pesan
		return redirect()->back()->with('success_msg', 'Divisi berhasil ditambahkan');
	}

	public function edit(Divisi $divisi)
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

	public function update(Request $request, Divisi $divisi)
	{
		// validating every form input
		$request->validate([
			'kkkk' => [],
		]);

		// mempersiapkan data untuk disimpan ke basis data
		$data = [
			'kkkk' => $request->kkkk,
		];

		// menyimpan ke basisdata
		$divisi->update($data);

		// mengalihkan ke halaman sebelumnya dengan pesan
		return redirect()->back()->with('success_msg', 'Divisi berhasil diperbarui');
	}

	public function destroy(Divisi $divisi)
	{
		// menghapus data dari basisdata
		$divisi->delete();
		// mengalihkan ke halaman sebelumnya dengan pesan
		return redirect()->back()->with('success_msg', 'Divisi berhasil dihapus');
	}

}
