@extends('layouts.vertical', ['subtitle' => 'Proposals'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Proposals'])
<form method="GET" class="mb-4">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Pesquisar" class="form-control w-25" />
</form>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Valor</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($proposals as $proposal)
            <tr>
                <td>{{ $proposal->id }}</td>
                <td>{{ $proposal->client_name }}</td>
                <td>{{ $proposal->amount }}</td>
                <td><a href="{{ route('admin.proposals.edit', $proposal) }}">Editar</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $proposals->links() }}
@endsection
