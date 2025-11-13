@extends('layouts.dashboard')

@section('contents')
<h1 class="text-2xl font-semibold text-gray-900 mb-6">Tambah Kamar Baru</h1>

<form action="{{ route('dashboard.rooms.store') }}" method="POST">
    @include('dashboard.rooms._form', ['roomTypes' => $roomTypes])
</form>
@endsection