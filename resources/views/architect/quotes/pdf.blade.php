<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body        { font-family: DejaVu Sans, sans-serif; color: #1a1d14; font-size: 13px; }
        .header     { display: flex; justify-content: space-between; margin-bottom: 40px; }
        .logo       { font-size: 24px; font-weight: bold; color: #4e7228; }
        .ref        { font-size: 12px; color: #8a8c80; margin-top: 4px; }
        h1          { font-size: 20px; color: #2d4a0f; margin-bottom: 6px; }
        .meta       { color: #5a5c52; font-size: 12px; margin-bottom: 30px; }
        table       { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th          { background: #f0f4eb; color: #4e7228; padding: 10px 12px;
                      text-align: left; font-size: 11px; text-transform: uppercase; }
        td          { padding: 10px 12px; border-bottom: 1px solid #f0f0ec; font-size: 13px; }
        .text-right { text-align: right; }
        .totals     { width: 260px; margin-left: auto; }
        .totals td  { border: none; padding: 6px 12px; }
        .total-ttc  { font-size: 16px; font-weight: bold; color: #2d4a0f; border-top: 2px solid #4e7228; }
        .footer     { margin-top: 50px; font-size: 11px; color: #8a8c80; text-align: center;
                      border-top: 1px solid #e8e4dc; padding-top: 12px; }
    </style>
</head>
<body>

    {{-- En-tête --}}
    <div class="header">
        <div>
            <div class="logo">Spacely</div>
            <div class="ref">Devis {{ $quote->reference }}</div>
        </div>
        <div style="text-align:right; color:#5a5c52; font-size:12px;">
            <p>{{ $quote->booking->timeSlot->availability->architectProfile->user->name }}</p>
            <p>{{ $quote->booking->timeSlot->availability->architectProfile->city }}</p>
            <p>Émis le {{ $quote->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    {{-- Titre --}}
    <h1>Devis {{ $quote->reference }}</h1>
    <div class="meta">
        Client : <strong>{{ $quote->booking->clientProfile->user->name }}</strong> &nbsp;|&nbsp;
        Email : {{ $quote->booking->clientProfile->user->email }} &nbsp;|&nbsp;
        Consultation : {{ $quote->booking->timeSlot->start_at->format('d/m/Y à H:i') }}
    </div>

    {{-- Lignes --}}
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Qté</th>
                <th class="text-right">Prix unit. (MAD)</th>
                <th class="text-right">Total HT (MAD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quote->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totaux --}}
    <table class="totals">
        <tr>
            <td>Total HT</td>
            <td class="text-right">{{ number_format($quote->total_ht, 2) }} MAD</td>
        </tr>
        <tr>
            <td>TVA ({{ $quote->tva }}%)</td>
            <td class="text-right">
                {{ number_format($quote->total_ht * ($quote->tva / 100), 2) }} MAD
            </td>
        </tr>
        <tr class="total-ttc">
            <td><strong>Total TTC</strong></td>
            <td class="text-right"><strong>{{ number_format($quote->total_ttc, 2) }} MAD</strong></td>
        </tr>
    </table>

    {{-- Pied de page --}}
    <div class="footer">
        Spacely — Plateforme de mise en relation architectes et clients &nbsp;|&nbsp;
        Ce devis est valable 30 jours à compter de sa date d'émission.
    </div>

</body>
</html>