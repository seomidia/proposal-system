<h1>Editar Proposta</h1>
<form method="post" action="{{ route('proposals.update', $proposal) }}">
    @csrf
    @method('put')
    <label>Cliente</label>
    <input name="client_name" value="{{ $proposal->client_name }}" />
    <label>Email</label>
    <input name="client_email" value="{{ $proposal->client_email }}" />
    <label>Telefone</label>
    <input name="client_phone" value="{{ $proposal->client_phone }}" />
    <button type="submit">Salvar</button>
</form>
