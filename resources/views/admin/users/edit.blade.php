@extends('layouts.vertical', ['subtitle' => 'Edit User'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Edit User'])
<form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Nome</label>
        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required />
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required />
    </div>
    <div class="mb-3">
        <label>Senha (deixe em branco para manter)</label>
        <input type="password" name="password" class="form-control" />
    </div>
    <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" name="is_admin" value="1" {{ $user->is_admin ? 'checked' : '' }} />
        <label class="form-check-label">Administrador</label>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
</form>
@endsection
