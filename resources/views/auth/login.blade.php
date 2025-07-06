<h1>Login</h1>
@if($errors->any())
<p>{{ $errors->first('email') }}</p>
@endif
<form method="post" action="{{ route('login') }}">
    @csrf
    <label>Email</label>
    <input name="email" type="email" />
    <label>Senha</label>
    <input name="password" type="password" />
    <button type="submit">Entrar</button>
</form>
