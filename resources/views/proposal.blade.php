@extends('layouts.base')

@section('content')
    <div class="container py-4">
        <h1 class="text-2xl font-bold mb-4">Proposta #{{ $proposal->id }}</h1>
        <p><strong>Cliente:</strong> {{ $proposal->client_name }}</p>
        <p><strong>Email:</strong> {{ $proposal->client_email }}</p>
        <p><strong>Telefone:</strong> {{ $proposal->client_phone }}</p>
        <p><strong>Valor:</strong> {{ $proposal->amount }}</p>
        <p><strong>Vencimento:</strong> {{ optional($proposal->due_date)->format('d/m/Y') }}</p>

        @if($proposal->custom_fields)
            <h2 class="text-xl font-semibold mt-4">Dados adicionais</h2>
            <ul>
                @foreach($proposal->custom_fields as $key => $value)
                    <li><strong>{{ $key }}:</strong> {{ $value }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
