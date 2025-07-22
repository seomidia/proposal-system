# Proposta API Shortcodes

Este plugin para WordPress busca informações de uma proposta no backend Laravel.
Ele utiliza o parâmetro `proposta` presente na URL da página para definir o ID a ser consultado na API em `https://maxxidoctor.com.br/api/proposals/{id}`.

## Uso

1. Copie a pasta `proposta-plugin` para o diretório `wp-content/plugins` do seu WordPress.
2. (Opcional) defina a constante `PROPOSTA_API_TOKEN` no `wp-config.php` caso a API exija autenticação JWT. O token obtido automaticamente pelo plugin é armazenado em um *transient* por uma hora para evitar requisições repetidas.
3. Ative o plugin no painel de administração.
4. Utilize os shortcodes abaixo para exibir cada campo da proposta informada na URL:

```
[proposta_id]
[proposta_client_name]
[proposta_client_email]
[proposta_client_phone]
[proposta_amount]
[proposta_due_date]
[proposta_kommo_lead_id]
[proposta_faturamento_medio_mensal]
[proposta_faturamento_medio_anual]
[proposta_quantidade_socios_contrato]
[proposta_tributacao_federal]
[proposta_media_declaracoes_ano]
[proposta_media_lancamentos_mes]
[proposta_quantos_funcionarios]
[proposta_proposal_url]
[proposta_tipo_proposta]
[proposta_economia_por_ano]
```

Para campos personalizados utilize:

```
[proposta_custom campo="nome_do_campo"]
```
