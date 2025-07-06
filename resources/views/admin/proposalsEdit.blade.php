@extends('layouts.vertical', ['subtitle' => 'Edit Proposal'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Edit Proposal'])
<form method="POST" action="{{ route('admin.proposals.update', $proposal) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Nome</label>
        <input type="text" name="client_name" class="form-control" value="{{ $proposal->client_name }}" />
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="text" name="client_email" class="form-control" value="{{ $proposal->client_email }}" />
    </div>
    <div class="mb-3">
        <label>Telefone</label>
        <input type="text" name="client_phone" class="form-control" value="{{ $proposal->client_phone }}" />
    </div>
    <div class="mb-3">
        <label>Valor</label>
        <input type="text" name="amount" class="form-control" value="{{ $proposal->amount }}" />
    </div>
    <div class="mb-3">
        <label>Data Vencimento</label>
        <input type="date" name="due_date" class="form-control" value="{{ $proposal->due_date?->format('Y-m-d') }}" />
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
</form>
@endsection
