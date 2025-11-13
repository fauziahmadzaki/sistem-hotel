@extends('layouts.dashboard')

@section('contents')
<h1 class="text-2xl font-semibold text-gray-900 mb-6">Edit Transaksi #{{ $transaction->id }}</h1>

<form action="{{ route('dashboard.transactions.update', $transaction) }}" method="POST">
    @method('PUT')
    @include('dashboard.transactions._form', ['transaction' => $transaction])
</form>
@endsection