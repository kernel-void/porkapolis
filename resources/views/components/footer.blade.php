@php
    use App\Models\Setting;
    $pengaturan = Setting::first();
@endphp

<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>{{ $pengaturan->footer ?? 'Copyright © Pembayaran SPP ' . date('Y') }}</span>
        </div>
    </div>
</footer>
