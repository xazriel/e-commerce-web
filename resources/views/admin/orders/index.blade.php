<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-10">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold uppercase tracking-widest">Admin: All Transactions</h1>

            <a href="{{ route('admin.orders.export', request()->query()) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-[10px] font-bold uppercase tracking-widest rounded-lg transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                </svg>
                Export Excel
            </a>
        </div>

        {{-- Filter Bar --}}
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-wrap gap-3 mb-6">
            <select name="status"
                    class="text-[11px] uppercase tracking-widest border border-gray-200 rounded-lg px-3 py-2 text-gray-600 focus:outline-none focus:ring-1 focus:ring-gray-300">
                <option value="">All Status</option>
                <option value="pending"    {{ request('status') == 'pending'    ? 'selected' : '' }}>Pending</option>
                <option value="paid"       {{ request('status') == 'paid'       ? 'selected' : '' }}>Paid</option>
                <option value="shipped"    {{ request('status') == 'shipped'    ? 'selected' : '' }}>Shipped</option>
                <option value="success"  {{ request('status') == 'success'  ? 'selected' : '' }}>Success</option>
                <option value="cancelled"  {{ request('status') == 'cancelled'  ? 'selected' : '' }}>Cancelled</option>
            </select>

            <input type="date" name="start_date" value="{{ request('start_date') }}"
                   class="text-[11px] border border-gray-200 rounded-lg px-3 py-2 text-gray-600 focus:outline-none focus:ring-1 focus:ring-gray-300">

            <input type="date" name="end_date" value="{{ request('end_date') }}"
                   class="text-[11px] border border-gray-200 rounded-lg px-3 py-2 text-gray-600 focus:outline-none focus:ring-1 focus:ring-gray-300">

            <button type="submit"
                    class="px-4 py-2 bg-gray-900 text-white text-[10px] font-bold uppercase tracking-widest rounded-lg hover:bg-gray-700 transition">
                Filter
            </button>

            @if(request()->hasAny(['status', 'start_date', 'end_date']))
            <a href="{{ route('admin.orders.index') }}"
               class="px-4 py-2 border border-gray-200 text-gray-500 text-[10px] font-bold uppercase tracking-widest rounded-lg hover:bg-gray-50 transition">
                Reset
            </a>
            @endif
        </form>

        {{-- Table --}}
        <div class="bg-white border border-gray-100 shadow-sm rounded-xl overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="p-4 text-[10px] uppercase tracking-widest text-gray-400">Order & Customer</th>
                        <th class="p-4 text-[10px] uppercase tracking-widest text-gray-400">Total</th>
                        <th class="p-4 text-[10px] uppercase tracking-widest text-gray-400">Status</th>
                        <th class="p-4 text-[10px] uppercase tracking-widest text-gray-400">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4">
                            <div class="text-[12px] font-bold">#{{ $order->order_number }}</div>
                            <div class="text-[10px] text-gray-400 uppercase">{{ $order->user->name }}</div>
                        </td>
                        <td class="p-4 text-[11px] font-medium italic">
                            IDR {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="p-4">
                            <span class="px-3 py-1 text-[9px] font-bold uppercase rounded-full
                                {{ $order->status == 'paid' ? 'bg-green-100 text-green-600' :
                                   ($order->status == 'shipped' ? 'bg-blue-100 text-blue-600' :
                                   ($order->status == 'success' ? 'bg-gray-800 text-white' :
                                   ($order->status == 'cancelled' ? 'bg-red-100 text-red-500' :
                                   'bg-gray-100 text-gray-400'))) }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="p-4">
                            <a href="{{ route('admin.orders.show', $order->order_number) }}"
                               class="text-[10px] font-bold text-blue-500 uppercase tracking-widest hover:underline">
                                Process Order
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-10 text-center text-[11px] text-gray-400 uppercase tracking-widest">
                            No orders found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-gray-100">
                {{ $orders->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
