@extends('layouts.app')

@section('content')
<div class="row justify-content-center" style="padding: 30px;">
    <generator :modul="{{$modul->toJson()}}" :modul-lainnya="{{ $modul_lainnya->toJson() }}"></generator>
</div>
@endsection
