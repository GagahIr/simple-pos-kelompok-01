@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
<h1 class="text-lg font-semibold mb-4">Transaksi Kasir</h1>
<div class="grid grid-cols-3 gap-4">
    @foreach ($products as $product)
       <div class="border rounded-md p-3 cursor-pointer flex justify-between"
     @click="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">
    <div>
        <div class="flex items-center gap-2">
            <p class="font-medium">{{ $product->name }}</p>
            @if ($product->stock < 10)
                <span class="text-xs font-medium bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">Stok Menipis</span>
            @endif
        </div>
        <p class="text-sm text-slate-500">Rp {{ number_format($product->price) }}</p>
    </div>
</div>
@endsection