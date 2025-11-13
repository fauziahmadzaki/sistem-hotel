@extends('layouts.dashboard')

@section('contents')
<h1 class="text-2xl font-semibold text-gray-900 mb-6">Edit User: {{ $user->name }}</h1>

<form action="{{ route('dashboard.users.update', $user) }}" method="POST">
    @method('PUT')
    @include('dashboard.users._form', [
    'user' => $user,
    'roles' => $roles
    ])
</form>
@endsection