<h1>Novo Usuário</h1>
<form method="post" action="{{ route('users.store') }}">
    @csrf
    <label>Nome</label>
    <input name="name" />
    <label>Email</label>
    <input name="email" />
    <label>Senha</label>
    <input name="password" type="password" />
    <label>Admin?</label>
    <input name="is_admin" type="checkbox" value="1" />
    <button type="submit">Salvar</button>
</form>
