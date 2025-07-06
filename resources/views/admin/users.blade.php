@extends('layouts.vertical', ['subtitle' => 'Users'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Users'])
<a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-2">Novo Usuário</a>
<div class="card">
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
</div>

@if (session('modalError'))
<div id="errorAlertModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content modal-filled bg-danger">
            <div class="modal-body">
                <div class="text-center">
                    <i class="bx bx-error display-6 mt-0 text-white"></i>
                    <h4 class="mt-3 text-white">Erro</h4>
                    <p class="mt-3">{{ session('modalError') }}</p>
                    <button type="button" class="btn btn-light mt-3" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
@if (session('modalError'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var errorModal = new bootstrap.Modal(document.getElementById('errorAlertModal'));
        errorModal.show();
    });
</script>
@endif
@endsection
