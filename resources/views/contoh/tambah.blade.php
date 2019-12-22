@extends('stisla.layouts.form')

@section('konten')
<div class="section-header">
  <h1>IKON {{ $title }}</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('NAMA_ROUTE.index') }}">NAMA_MODUL</a></div>
    <div class="breadcrumb-item">{{ $title }}</div>
  </div>
</div>

<div class="section-body">
  <form action="{{ $action }}" method="post" enctype="multipart/form-data">
    @isset($d)
    @method('PUT')
    @endisset
    @csrf
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>IKON {{ $title }}</h4>
            <a href="{{ route('NAMA_ROUTE.index') }}" class="btn btn-primary float-right">Lihat Data</a>
          </div>
          <div class="card-body">
            <div class="row">FORM
            </div>
          </div>
        </div>
      </div>
      GRUP_LAINNYA
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Aksi</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                <a href="{{ route('NAMA_ROUTE.index') }}" class="btn btn-secondary btn-block">Batal</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

IMPORT