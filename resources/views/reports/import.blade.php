@extends('layouts.app')

@section('title', 'Import Produk')

@section('content')
    <h1 class="text-lg font-semibold mb-4">Import Produk dari CSV</h1>

    @if (session('success'))
        <div class="bg-green-50 text-green-700 p-3 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('import.store') }}" enctype="multipart/form-data" class="max-w-md">
        @csrf
        <label class="block mb-3">
            <span class="text-sm font-medium">File CSV</span>
            <input type="file" name="csv" accept=".csv" class="mt-1 block w-full text-sm">
            @error('csv')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </label>
        <p class="text-xs text-slate-500 mb-3">Column yang dibutuhkan: <code>name</code>, <code>category_id</code>, <code>price</code>.</p>
        <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-md text-sm">Import</button>
    </form>

    @if (isset($importErrors) && $importErrors->isNotEmpty())
        <div class="mt-8 max-w-2xl">
            <h2 class="text-sm font-semibold text-red-600 mb-2">Baris yang Gagal Di-import ({{ $importErrors->count() }})</h2>
            <div class="overflow-x-auto border border-red-200 rounded-md bg-white">
                <table class="w-full text-left text-sm">
                    <thead class="bg-red-50 text-red-800 border-b border-red-200">
                        <tr>
                            <th class="px-4 py-2 w-24">Baris</th>
                            <th class="px-4 py-2">Pesan Error</th>
                            <th class="px-4 py-2 w-44 text-right">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-100">
                        @foreach ($importErrors as $error)
                            <tr class="hover:bg-red-50/50">
                                <td class="px-4 py-2 font-medium text-slate-700">Baris {{ $error->row }}</td>
                                <td class="px-4 py-2 text-red-700">{{ $error->message }}</td>
                                <td class="px-4 py-2 text-slate-500 text-right">{{ $error->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection