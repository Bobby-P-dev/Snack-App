<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: sans-serif; color: #333; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    </style>
</head>
<body class="bg-white">

    <div class="border-t-2 border-blue-600 pt-6">
        <div class="flex justify-between mb-8">
            <div>
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Informasi Pelanggan</h3>
                <p class="font-bold text-gray-800 text-lg">Nama: {{ $data['customer_name'] }}</p>
                <p class="text-gray-600 mt-1 text-sm">No. Telepon: {{ $data['customer_phone'] }}</p>
                <p class="text-gray-600 mt-1 text-sm max-w-xs leading-relaxed">Alamat: {{ $data['location'] }}</p>
            </div>
            <div class="text-right">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Detail Pesanan</h3>
                <p class="text-gray-800 font-medium text-sm">No. Pesanan: <span class="font-bold text-blue-600">{{ $data['order_number'] }}</span></p>
                <div class="mt-2 flex items-center gap-2">
                    @php
                        $isPending = strtolower($data['status']) === 'pending';
                        $displayStatus = $isPending ? 'Belum Dibayar' : 'Lunas';
                        $statusColor = $isPending ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700';
                    @endphp
                    <p class="text-gray-800 font-medium text-sm">Status Pembayaran: </p>
                    <span class="inline-block px-2.5 py-1 rounded text-xs font-bold uppercase {{ $statusColor }}">
                        {{ $displayStatus }}
                    </span>
                </div>
            </div>
        </div>

        <table class="w-full text-left mb-8 border-collapse">
            <thead>
                <tr class="bg-gray-50 border-y border-gray-200">
                    <th class="py-3 px-4 font-semibold text-gray-600 text-sm">Deskripsi Pembayaran</th>
                    <th class="py-3 px-4 font-semibold text-gray-600 text-sm text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($data['all_items']) && $data['all_items']->isNotEmpty())
                    @foreach($data['all_items'] as $item)
                    @php
                        $qty = $item->quantity;
                        $price = $item->price_at_order;
                        $subtotal = $qty * $price;
                    @endphp
                    <tr class="border-b border-gray-100 break-inside-avoid" style="page-break-inside: avoid;">
                        <td class="py-4 px-4 text-gray-800 text-sm align-top">
                            <div class="font-bold text-gray-900">{{ $item->product->name }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                Kategori: {{ str_replace('_', ' ', Str::title($item->type)) }}
                            </div>
                            <div class="text-xs text-gray-400 mt-1">
                                {{ $qty }} pcs x Rp {{ number_format($price, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="py-4 px-4 text-right text-gray-800 font-medium text-sm align-top">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <div class="flex justify-end break-inside-avoid" style="page-break-inside: avoid;">
            <div class="w-72">
                <div class="flex justify-between py-2.5 border-b border-gray-100">
                    <span class="text-gray-500 text-sm">Subtotal</span>
                    <span class="font-medium text-gray-800 text-sm">Rp {{ number_format($data['total_amount'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2.5 border-b border-gray-100">
                    <span class="text-gray-500 text-sm">DP ({{ $data['dp_percentage'] }}%)</span>
                    <span class="font-medium text-gray-800 text-sm">Rp {{ number_format($data['dp_amount'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-4">
                    <span class="font-bold text-gray-800 text-base">Sisa Tagihan</span>
                    <span class="font-bold text-blue-600 text-lg">Rp {{ number_format($data['total_amount'] - $data['dp_amount'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <div class="mt-16 text-center text-xs text-gray-400">
            <p>Harap melakukan pelunasan sisa tagihan pada saat pengiriman pesanan.</p>
            <p class="mt-1">Invoice ini sah dan diterbitkan secara otomatis oleh sistem.</p>
        </div>
    </div>

</body>
</html>