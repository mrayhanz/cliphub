@extends('layouts.admin')
@section('title', 'Pembayaran & Escrow')
@section('page_title', 'Pembayaran & Escrow')
@section('page_subtitle', 'Monitor aliran dana antara brand dan kreator')

@section('content')
@php
$txns = [
    ['id'=>'TXN-20001','brand'=>'Wardah Beauty','kreator'=>'Rafi Ananda','amount'=>'Rp 1.200.000','type'=>'Escrow In','date'=>'27 Mar 2026','status'=>'Berhasil'],
    ['id'=>'TXN-20002','brand'=>'Tokopedia','kreator'=>'Luna Aesthetic','amount'=>'Rp 2.500.000','type'=>'Payout','date'=>'26 Mar 2026','status'=>'Berhasil'],
    ['id'=>'TXN-20003','brand'=>'Shopee ID','kreator'=>'Hana Creator','amount'=>'Rp 4.800.000','type'=>'Escrow In','date'=>'25 Mar 2026','status'=>'Pending'],
    ['id'=>'TXN-20004','brand'=>'Samsung ID','kreator'=>'Dimas Viral','amount'=>'Rp 980.000','type'=>'Payout','date'=>'24 Mar 2026','status'=>'Berhasil'],
    ['id'=>'TXN-20005','brand'=>'Kopi Kenangan','kreator'=>'Rizky Clips','amount'=>'Rp 650.000','type'=>'Refund','date'=>'23 Mar 2026','status'=>'Diproses'],
];
@endphp
<div class="space-y-5">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([['label'=>'Total Escrow Berjalan','val'=>'Rp 248 Jt','icon'=>'vault','color'=>'brand'],['label'=>'Menunggu Payout','val'=>'Rp 32 Jt','icon'=>'clock','color'=>'amber'],['label'=>'Total Dibayarkan','val'=>'Rp 1.2 M','icon'=>'trending-up','color'=>'emerald'],['label'=>'Transaksi Bulan Ini','val'=>'1,847','icon'=>'receipt','color'=>'violet']] as $s)
        <div class="stat-card">
            <div class="flex w-9 h-9 rounded-xl bg-{{ $s['color'] }}/10 border border-{{ $s['color'] }}/20 items-center justify-center mb-3">
                <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4 text-{{ $s['color']==='brand'?'brand':$s['color'].'-400' }}"></i>
            </div>
            <p class="text-xl font-bold text-white">{{ $s['val'] }}</p>
            <p class="text-xs text-slate-500">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-neutral-800/60">
            <h3 class="text-sm font-semibold text-white">Riwayat Transaksi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead><tr class="border-b border-neutral-800/60">
                    @foreach(['ID Transaksi','Brand','Kreator','Jumlah','Tipe','Tanggal','Status',''] as $h)
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $h }}</th>
                    @endforeach
                </tr></thead>
                <tbody class="divide-y divide-neutral-800/40">
                    @foreach($txns as $t)
                    @php $sc=match($t['status']){'Berhasil'=>'bg-emerald-500/10 text-emerald-400 border-emerald-500/20','Pending'=>'bg-amber-500/10 text-amber-400 border-amber-500/20',default=>'bg-blue-500/10 text-blue-400 border-blue-500/20'}; @endphp
                    <tr class="hover:bg-white/[2%] transition-colors">
                        <td class="px-5 py-3.5 text-xs font-mono text-slate-400">{{ $t['id'] }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-300">{{ $t['brand'] }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-300">{{ $t['kreator'] }}</td>
                        <td class="px-5 py-3.5 text-sm font-semibold text-white">{{ $t['amount'] }}</td>
                        <td class="px-5 py-3.5"><span class="text-xs px-2 py-0.5 rounded-full bg-neutral-800 text-slate-400 border border-neutral-700">{{ $t['type'] }}</span></td>
                        <td class="px-5 py-3.5 text-xs text-slate-400">{{ $t['date'] }}</td>
                        <td class="px-5 py-3.5"><span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $sc }}">{{ $t['status'] }}</span></td>
                        <td class="px-5 py-3.5"><button class="p-1.5 rounded-lg text-slate-500 hover:text-white hover:bg-white/5 transition-colors"><i data-lucide="eye" class="w-3.5 h-3.5"></i></button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
