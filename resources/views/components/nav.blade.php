<nav class="bg-slate-900 text-white px-6 py-3 flex items-center justify-between shadow-sm">
    <div class="flex items-center gap-6">
        <span class="font-bold tracking-wide text-lg text-slate-100">Simple POS</span>
        
        <div class="flex items-center gap-4">
            <a href="{{ route('pos.create') }}" 
               class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-150 {{ request()->routeIs('pos.*') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                Kasir
            </a>

            <a href="{{ route('transactions.index') }}" 
               class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-150 {{ request()->routeIs('transactions.*') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/50' }}">
                Transaksi
            </a>
        </div>
    </div>
</nav>