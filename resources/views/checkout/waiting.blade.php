<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Waiting for Payment - Farhana</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FCFCFA] text-[#4A4A4A] antialiased">

    <main class="max-w-md mx-auto px-6 py-24 text-center">
        <h2 class="text-[12px] tracking-[0.5em] uppercase mb-12 text-[#8B864E]">Waiting for Payment</h2>
        
        <div class="bg-white p-10 border border-gray-100 shadow-sm mb-10 rounded-sm">
            <div id="timer-container" class="mb-6">
                <p class="text-[10px] bg-yellow-50 text-yellow-700 py-2 px-3 uppercase tracking-widest rounded-sm font-medium">
                    Items are reserved for <span id="countdown" class="font-bold">02:00</span>
                </p>
            </div>

            <p class="text-[9px] text-gray-400 tracking-[0.3em] uppercase mb-8 italic">Scan QRIS to Complete Purchase</p>
            
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $order->order_number }}" 
                 class="mx-auto mb-8 grayscale hover:grayscale-0 transition duration-500 rounded-lg shadow-sm" alt="QRIS">
            
            <div class="space-y-1">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest">Total Amount</p>
                <p class="text-[20px] font-light tracking-widest text-[#8B864E]">
                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div id="action-area">
            <form action="{{ route('checkout.simulatePay', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-black text-white py-4 text-[9px] font-bold uppercase tracking-[0.3em] rounded-full hover:bg-gray-800 transition shadow-lg mb-6">
                    Confirm Payment (Simulation)
                </button>
            </form>
        </div>
    </main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tentukan durasi dalam detik (2 menit = 120 detik)
        // Kita gunakan 120 sebagai fallback jika ada masalah data
        let duration = 120; 

        // Ambil data deadline dari server untuk cek apakah sebenarnya sudah expired
        const deadlineStr = "{{ $order->payment_deadline }}"; 
        
        // Fungsi parsing manual untuk menghindari masalah timezone
        function parseDateManual(dateStr) {
            const t = dateStr.split(/[- :]/);
            return new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
        }

        const deadline = parseDateManual(deadlineStr).getTime();
        const nowServer = new Date().getTime();
        
        // Hitung sisa waktu yang sebenarnya (dalam detik)
        let timeLeft = Math.floor((deadline - nowServer) / 1000);

        // PROTEKSI: Jika timeLeft tiba-tiba ngaco (lebih dari 2 menit atau 120 detik)
        // Kita paksa maksimal hanya 120 detik saja.
        if (timeLeft > 120) {
            timeLeft = 120;
        }

        const countdownDisplay = document.querySelector('#countdown');

        function updateTimer() {
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                countdownDisplay.textContent = "00:00";
                
                // Refresh halaman untuk memicu pembatalan di Controller
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                return;
            }

            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;

            countdownDisplay.textContent = 
                (minutes < 10 ? "0" + minutes : minutes) + ":" + 
                (seconds < 10 ? "0" + seconds : seconds);
            
            timeLeft--;
        }

        updateTimer();
        const timerInterval = setInterval(updateTimer, 1000);
    });
</script>
</body>
</html>