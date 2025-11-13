@extends('layouts.dashboard')

@section('contents')
<h1 class="text-2xl font-semibold text-gray-900 mb-6">Edit Tipe Kamar: {{ $roomType->room_type_name }}</h1>

<form action="{{ route('dashboard.room-types.update', $roomType) }}" method="POST">
    @method('PUT')
    @include('dashboard.room-types._form', [
    'roomType' => $roomType,
    'facilities' => $facilities,
    'attachedFacilities' => $attachedFacilities
    ])
</form>
@endsection