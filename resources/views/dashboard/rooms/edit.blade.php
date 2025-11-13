@extends('layouts.dashboard')

@section('contents')
<h1 class="text-2xl font-semibold text-gray-900 mb-6">Edit Kamar: {{ $room->room_name }}</h1>

<form action="{{ route('dashboard.rooms.update', $room) }}" method="POST">
    @method('PUT')
    @include('dashboard.rooms._form', [
    'room' => $room,
    'roomTypes' => $roomTypes
    ])
</form>
@endsection