<h1>Propostas</h1>
<table>
<thead><tr><th>Cliente</th><th>Valor</th><th>Ações</th></tr></thead>
<tbody>
@foreach($proposals as $p)
<tr>
<td>{{ $p->client_name }}</td>
<td>{{ $p->amount }}</td>
<td><a href="{{ route('proposals.edit', $p) }}">Editar</a></td>
</tr>
@endforeach
</tbody>
</table>
{{ $proposals->links() }}
