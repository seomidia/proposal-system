<h1>Usuários</h1>
<table>
<thead><tr><th>Nome</th><th>Email</th><th>Ações</th></tr></thead>
<tbody>
@foreach($users as $u)
<tr>
<td>{{ $u->name }}</td>
<td>{{ $u->email }}</td>
<td><a href="{{ route('users.edit', $u) }}">Editar</a></td>
</tr>
@endforeach
</tbody>
</table>
<a href="{{ route('users.create') }}">Novo Usuário</a>
{{ $users->links() }}
