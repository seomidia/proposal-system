<h1>Dashboard</h1>
<p>Total de propostas: {{ $count }}</p>
<ul>
@foreach($latest as $proposal)
<li>{{ $proposal->client_name }} - {{ $proposal->amount }}</li>
@endforeach
</ul>
