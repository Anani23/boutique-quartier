<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #111; margin: 0; padding: 10px; }
        h1 { font-size: 13px; text-align: center; margin: 0 0 2px; }
        .center { text-align: center; }
        .muted { color: #555; }
        hr { border: none; border-top: 1px dashed #999; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 2px 0; vertical-align: top; }
        .right { text-align: right; }
        .row { display: flex; justify-content: space-between; }
        .total { font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>
    <h1>{{ $vente->boutique->nom }}</h1>
    @if ($vente->boutique->adresse)
        <p class="center muted">{{ $vente->boutique->adresse }}</p>
    @endif
    @if ($vente->boutique->telephone)
        <p class="center muted">{{ $vente->boutique->telephone }}</p>
    @endif

    <hr>

    <table>
        <tr><td>Reçu</td><td class="right">{{ $vente->numero_recu }}</td></tr>
        <tr><td>Date</td><td class="right">{{ $vente->created_at->format('d/m/Y H:i') }}</td></tr>
        <tr><td>Vendeur</td><td class="right">{{ $vente->vendeur->name }}</td></tr>
    </table>

    <hr>

    <table>
        <thead>
            <tr>
                <td><strong>Produit</strong></td>
                <td class="right"><strong>Qté</strong></td>
                <td class="right"><strong>Total</strong></td>
            </tr>
        </thead>
        <tbody>
            @foreach ($vente->lignes as $ligne)
                <tr>
                    <td>{{ $ligne->nom_produit }}</td>
                    <td class="right">{{ $ligne->quantite }}</td>
                    <td class="right">{{ number_format($ligne->sous_total, 0, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <table>
        <tr>
            <td class="total">TOTAL</td>
            <td class="right total">{{ number_format($vente->total, 0, ',', ' ') }} FCFA</td>
        </tr>
    </table>

    <hr>
    <p class="center muted">Merci de votre visite !</p>
</body>
</html>
