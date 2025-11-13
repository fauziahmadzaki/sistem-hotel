@extends('layouts.dashboard')

@section('contents')
<h1 class="text-2xl font-semibold text-gray-900 mb-6">Tambah User Baru</h1>

<form action="{{ route('dashboard.users.store') }}" method="POST">
    @include('dashboard.users._form', ['roles' => $roles])
</form>
@endsection