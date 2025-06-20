<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Venda #{{ $sale->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }

        h1, h2, h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
            margin-bottom: 8px;
            padding-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .total {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            margin-top: 15px;
        }

        .info-block p {
            margin: 2px 0;
        }
    </style>
</head>
<body>
	<img src="{{ public_path('images/logo.png') }}" alt="Logo" style="width: 120px;">
    <h1>Recibo de Venda</h1>
    <h3>Venda #{{ $sale->id }}</h3>

    <div class="section">
        <div class="section-title">Dados do Cliente</div>
        <div class="info-block">
            <p><strong>Nome:</strong> {{ $client->name }}</p>
            <p><strong>CPF:</strong> {{ $client->cpf }}</p>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Itens da Venda</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Produto</th>
                    <th>Qtd</th>
                    <th>Preço</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>R$ {{ $item['price'] }}</td>
                        <td>R$ {{ $item['subtotal'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Parcelas</div>
        <table>
            <thead>
                <tr>
                    <th>Parcela</th>
                    <th>Vencimento</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($parcels as $parcel)
                    <tr>
                        <td>{{ $parcel['numero'] }}</td>
                        <td>{{ $parcel['vencimento'] }}</td>
                        <td>R$ {{ $parcel['valor'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="total">
        Total da Venda: R$ {{ number_format($sale->total, 2, ',', '') }}
    </div>
</body>
</html>
