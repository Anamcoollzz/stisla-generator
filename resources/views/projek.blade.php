@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">Tambah Projek</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <form action="{{ route('projek.tambah') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input required="required" type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama">
                            @error('nama') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="path">Path</label>
                            <input required="required" type="text" class="form-control @error('path') is-invalid @enderror" id="path" name="path">
                            @error('path') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Data Projek</div>

                <div class="card-body">
                    <table class="table table-striped table-bordered table-hovered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Projek</th>
                                <th>Path</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->nama }}</td>
                                <td>{{ $d->path }}</td>
                                <td>
                                    <a href="{{ route('modul', [$d->id]) }}" class="btn btn-primary">Lihat Modul</a>
                                    <a href="{{ route('projek.hapus', [$d->id]) }}" class="btn btn-danger">Hapus</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
