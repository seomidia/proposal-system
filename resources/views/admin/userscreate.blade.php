@extends('layouts.vertical', ['subtitle' => 'Create User'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Create User'])
<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    <div class="mb-3">
        <label>Nome</label>
        <input type="text" name="name" class="form-control" required />
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required />
    </div>
    <div class="mb-3">
        <label>Senha</label>
        <input type="password" name="password" class="form-control" required />
    </div>
    <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" name="is_admin" value="1" />
        <label class="form-check-label">Administrador</label>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
</form>
@endsection
