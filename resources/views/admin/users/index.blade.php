@extends('layouts.vertical', ['subtitle' => 'Users'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Users'])
<a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-2">Novo Usuário</a>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Admin</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->is_admin ? 'Sim' : 'Não' }}</td>
            <td>
                <a href="{{ route('admin.users.edit', $user) }}">Editar</a>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Excluir?')">Excluir</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $users->links() }}
@endsection
