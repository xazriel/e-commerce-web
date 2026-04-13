<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-10">
        <h1 class="text-2xl font-bold uppercase tracking-widest mb-8">Admin: All Transactions</h1>

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
                    @foreach($orders as $order)
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
                                {{ $order->status == 'paid' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="p-4">
                            <a href="{{ route('admin.orders.show', $order->order_number) }}" class="text-[10px] font-bold text-blue-500 uppercase tracking-widest hover:underline">
                                Process Order
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>