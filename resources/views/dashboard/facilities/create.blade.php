@extends('layouts.dashboard')

@section('contents')
<h1 class="text-2xl font-semibold text-gray-900 mb-6">Tambah Fasilitas Baru</h1>

<form action="{{ route('dashboard.facilities.store') }}" method="POST">
    @include('dashboard.facilities._form')
</form>
@endsection