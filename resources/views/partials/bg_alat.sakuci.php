<style>
    /* Latar belakang halaman login: pink soft + semi gold */
    .bg-alat {
        position: fixed;
        inset: 0;
        z-index: -1;
        overflow: hidden;
        pointer-events: none;
        background:
            radial-gradient(circle at 15% 20%, rgba(212,175,55,0.22), transparent 45%),
            radial-gradient(circle at 85% 80%, rgba(237,143,188,0.30), transparent 50%),
            linear-gradient(135deg, #fff5f8 0%, #fde8f0 50%, #fbeed2 100%);
    }
    [data-bs-theme="dark"] .bg-alat {
        background:
            radial-gradient(circle at 15% 20%, rgba(212,175,55,0.15), transparent 45%),
            radial-gradient(circle at 85% 80%, rgba(237,143,188,0.15), transparent 50%),
            linear-gradient(135deg, #1f1a1d 0%, #2a2025 50%, #33291c 100%);
    }

    /* lingkaran hiasan */
    .bg-alat .bulat {
        position: absolute;
        border-radius: 50%;
        border: 2px dashed rgba(212,175,55,0.35);
    }
    .bg-alat .bulat.b1 { width: 320px; height: 320px; top: -80px; left: -80px; }
    .bg-alat .bulat.b2 { width: 220px; height: 220px; bottom: -60px; right: 8%; border-color: rgba(237,143,188,0.45); }

    /* emoji alat melayang */
    .bg-alat .alat-bg {
        position: absolute;
        opacity: .55;
        filter: drop-shadow(0 6px 10px rgba(0,0,0,0.12));
        animation: bg-melayang 6s ease-in-out infinite;
    }
    @keyframes bg-melayang {
        0%, 100% { transform: translateY(0) rotate(-4deg); }
        50%      { transform: translateY(-18px) rotate(4deg); }
    }

    /* sinyal berkedip */
    .bg-alat .sinyal-bg {
        position: absolute;
        width: 12px; height: 12px;
        border-radius: 50%;
        background: #d4af37;
        animation: bg-kedip 2s ease-in-out infinite;
    }
    @keyframes bg-kedip {
        0%, 100% { opacity: .9; transform: scale(1); }
        50%      { opacity: .2; transform: scale(1.8); }
    }

    @media (max-width: 575.98px) {
        .bg-alat .alat-bg { font-size: 30px !important; opacity: .4; }
    }
    @media (prefers-reduced-motion: reduce) {
        .bg-alat .alat-bg, .bg-alat .sinyal-bg { animation: none; }
    }
</style>

<div class="bg-alat" aria-hidden="true">
    <div class="bulat b1"></div>
    <div class="bulat b2"></div>

    <div class="alat-bg" style="top: 8%;  left: 28%; font-size: 52px; animation-delay: 0s;">💻</div>
    <div class="alat-bg" style="top: 14%; right: 12%; font-size: 60px; animation-delay: .8s;">🖥️</div>
    <div class="alat-bg" style="top: 42%; left: 24%; font-size: 44px; animation-delay: 1.6s;">📡</div>
    <div class="alat-bg" style="top: 48%; right: 8%;  font-size: 48px; animation-delay: 2.4s;">🖧</div>
    <div class="alat-bg" style="bottom: 12%; left: 26%; font-size: 50px; animation-delay: 3.2s;">⌨️</div>
    <div class="alat-bg" style="bottom: 8%;  right: 24%; font-size: 46px; animation-delay: 4s;">🔌</div>
    <div class="alat-bg" style="top: 28%; left: 44%; font-size: 36px; animation-delay: 1s;">🖱️</div>
    <div class="alat-bg" style="bottom: 28%; right: 38%; font-size: 38px; animation-delay: 2s;">🌐</div>
    <div class="alat-bg" style="bottom: 40%; left: 6%;  font-size: 40px; animation-delay: 2.8s;">🎧</div>

    <span class="sinyal-bg" style="top: 40%; left: 27%;"></span>
    <span class="sinyal-bg" style="top: 12%; right: 20%; animation-delay: 1s;"></span>
</div>