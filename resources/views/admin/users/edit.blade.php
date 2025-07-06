<h1>Editar Usuário</h1>
<form method="post" action="{{ route('users.update', $user) }}">
    @csrf
    @method('put')
    <label>Nome</label>
    <input name="name" value="{{ $user->name }}" />
    <label>Email</label>
    <input name="email" value="{{ $user->email }}" />
    <label>Senha (deixar vazio para manter)</label>
    <input name="password" type="password" />
    <label>Admin?</label>
    <input name="is_admin" type="checkbox" value="1" {{ $user->is_admin ? 'checked' : '' }} />
    <button type="submit">Salvar</button>
</form>
<form method="post" action="{{ route('users.destroy', $user) }}">
    @csrf
    @method('delete')
    <button type="submit">Excluir</button>
</form>
