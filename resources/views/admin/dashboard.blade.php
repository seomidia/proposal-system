@extends('layouts.vertical', ['subtitle' => 'Dashboard'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Dashboard'])
    <div class="mb-4">
        <p>Total de Propostas: {{ $proposals_count }}</p>
        <p>Total de Usuários: {{ $users_count }}</p>
    </div>
    <h5>Últimas Propostas</h5>
    <ul>
        @foreach($latest_proposals as $p)
            <li><a href="{{ route('proposals.show', $p) }}">{{ $p->client_name }} (#{{ $p->id }})</a></li>
        @endforeach
    </ul>
@endsection
