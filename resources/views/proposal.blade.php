<!DOCTYPE html>
<html>
<head>
    <title>Proposta</title>
</head>
<body>
    <h1>Proposta para {{ $proposal->client_name }}</h1>
    <p>Valor: {{ $proposal->amount }}</p>
    <p>Vencimento: {{ optional($proposal->due_date)->format('d/m/Y') }}</p>
    <div>
        <h3>Campos Personalizados</h3>
        <pre>{{ json_encode($proposal->custom_fields, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
</body>
</html>
