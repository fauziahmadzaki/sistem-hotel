@extends('layouts.dashboard')

@section('contents')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Reservasi Dibatalkan</h1>
</div>

@include('dashboard.reservations._reservation_table', [
'reservations' => $reservations,
'pageType' => 'cancelled'
])
@endsection