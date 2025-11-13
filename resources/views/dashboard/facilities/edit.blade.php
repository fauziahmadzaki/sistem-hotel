@extends('layouts.dashboard')

@section('contents')
<h1 class="text-2xl font-semibold text-gray-900 mb-6">Edit Fasilitas: {{ $facility->facility_name }}</h1>

<form action="{{ route('dashboard.facilities.update', $facility) }}" method="POST">
    @method('PUT')
    @include('dashboard.facilities._form', ['facility' => $facility])
</form>
@endsection