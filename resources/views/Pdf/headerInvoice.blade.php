@php
    $logoPath = public_path('images/padukue-icon.png');
    if (!file_exists($logoPath)) {
        $logoPath = public_path('storage/padukue.webp');
    }
    $logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
    $mimeType = (file_exists($logoPath) && str_ends_with($logoPath, '.png')) ? 'image/png' : 'image/webp';
    $companyName = \App\Models\CmsSetting::where('key', 'company_name')->value('value') ?? 'Padu Kue';
    $companyPhone = \App\Models\CmsSetting::where('key', 'contact_phone')->value('value') ?? '0812-3456-7890';
    $companyAddress = \App\Models\CmsSetting::where('key', 'company_address')->value('value') ?? 'Bandung, Jawa Barat';
@endphp
<div style="font-size: 12px; font-family: sans-serif; display: flex; justify-content: space-between; align-items: center; padding: 0 15mm; width: 100%; box-sizing: border-box; padding-top: 10mm;">
    <div style="display: flex; align-items: center; gap: 15px;">
        @if($logoBase64)
            <img src="data:{{ $mimeType }};base64,{{ $logoBase64 }}" alt="Logo" style="width: 65px; height: 65px; border-radius: 10px; object-fit: contain;">
        @else
            <div style="width: 46px; height: 46px; border-radius: 10px; background: #e04a55; color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 18px;">
                PK
            </div>
        @endif
        <div style="margin-left: 12px;">
            <h1 style="margin: 0; font-size: 18px; color: #1c1917; font-weight: 800;">{{ $companyName }}</h1>
            <p style="margin: 3px 0 0 0; color: #78716c; font-size: 11px;">Spesialis Custom Snack Box & Aneka Kue</p>
        </div>
    </div>
    
    <div style="text-align: right;">
        <h2 style="margin: 0; font-size: 16px; color: #e04a55; font-weight: 800; letter-spacing: 1px;">INVOICE PESANAN</h2>
        <p style="margin: 4px 0 0 0; color: #78716c; font-size: 11px;">{{ $companyAddress }}<br>WhatsApp: {{ $companyPhone }}</p>
    </div>
</div>