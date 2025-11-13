@extends('layouts.dashboard')

@section('contents')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Edit Reservasi #{{ $reservation->id }}</h1>
</div>

{{-- Tampilkan error global --}}
@if ($errors->any())
<div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    <strong class="font-bold">Oops! </strong>
    <span class="block sm:inline">Terjadi kesalahan. Silakan periksa input Anda.</span>
</div>
@endif

<form action="{{ route('dashboard.reservations.update', $reservation) }}" method="POST">
    @method('PUT')
    @include('dashboard.reservations._form', [
    'reservation' => $reservation,
    'rooms' => $allRooms
    ])
</form>
@endsection