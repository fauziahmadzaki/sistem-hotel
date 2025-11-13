@extends('layouts.dashboard')

@section('contents')
<h1 class="text-2xl font-semibold text-gray-900 mb-6">Catat Transaksi Manual</h1>

<form action="{{ route('dashboard.transactions.store') }}" method="POST">
    @include('dashboard.transactions._form')
</form>
@endsection