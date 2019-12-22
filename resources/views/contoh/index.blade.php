@extends('stisla.layouts.table')

@section('konten')
<div class="section-header">
  <h1>IKON {{ $title }}</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item">{{ $title }}</div>
  </div>
</div>

<div class="section-body">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>
            IKON Data {{ $title }}
          </h4>
          <a href="{{ route('ROUTE.create') }}" class="btn btn-primary float-right">
            <i class="fa fa-plus"></i> Baru
          </a>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped" id="datatable">
              <thead>
                <tr>
                  <th>#</th>KOLOM_HEAD_TABEL
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data as $d)
                <tr>
                  <td>{{ $loop->iteration }}</td>KOLOM_BODY_TABEL
                  <td>
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton{{$loop->iteration}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Aksi
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item has-icon" href="{{ route('ROUTE.edit', $d->id) }}">
                        <i class="fas fa-edit"></i> Ubah
                      </a>
                      <a onclick="hapus(event, '{{ route('ROUTE.destroy', $d->id) }}')" class="dropdown-item has-icon" href="#">
                        <i class="fas fa-trash"></i> Hapus
                      </a>
                    </div>
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
</div>
@endsection

@push('script')
<form action="" id="form-hapus" method="post">
  @csrf
  @method('DELETE')
</form>
<script>
  function hapus(e, url) {
    swal({
      title: 'Anda yakin?',
      text: 'Sekali dihapus, data tidak akan kembali lagi!',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
      buttons : {
        cancel: {
          text: "Batal",
          value: null,
          visible: true,
          className: "",
          closeModal: true,
        },
        confirm: {
          text: "Lanjutkan",
        }
      }
    })
    .then((willDelete) => {
      if (willDelete) {
        $('#form-hapus').attr('action', url);
        document.getElementById('form-hapus').submit();
      } else {
        swal('Okay, tidak jadi');
      }
    });
  }

</script>
@endpush

@push('modal')

@endpush

@push('js')

@endpush