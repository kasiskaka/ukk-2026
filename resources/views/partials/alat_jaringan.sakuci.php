<style>
    .alat-deco {
        position: relative;
        height: 92px;
        margin-top: 18px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(212,175,55,0.12), rgba(237,143,188,0.16));
        border: 1px dashed rgba(212,175,55,0.45);
        overflow: hidden;
    }

    /* garis kabel yang bergerak */
    .alat-deco::before {
        content: "";
        position: absolute;
        left: 0; right: 0; top: 50%;
        height: 3px;
        background: repeating-linear-gradient(90deg, #d4af37 0 14px, transparent 14px 26px);
        opacity: .55;
        animation: kabel-jalan 3s linear infinite;
    }

    .alat-item {
        position: absolute;
        top: 50%;
        width: 52px; height: 52px;
        margin-top: -26px;
        display: flex; align-items: center; justify-content: center;
        font-size: 26px;
        background: #fff;
        border: 1px solid rgba(212,175,55,0.4);
        border-radius: 14px;
        box-shadow: 0 6px 14px rgba(0,0,0,0.08);
        animation: alat-melayang 3.2s ease-in-out infinite;
    }

    .alat-item:nth-child(1) { left: 4%;  animation-delay: 0s; }
    .alat-item:nth-child(2) { left: 20%; animation-delay: .4s; }
    .alat-item:nth-child(3) { left: 36%; animation-delay: .8s; }
    .alat-item:nth-child(4) { left: 52%; animation-delay: 1.2s; }
    .alat-item:nth-child(5) { left: 68%; animation-delay: 1.6s; }
    .alat-item:nth-child(6) { left: 84%; animation-delay: 2s; }

    /* ikon antena berkedip seperti sinyal */
    .alat-item.sinyal::after {
        content: "";
        position: absolute;
        top: -4px; right: -4px;
        width: 10px; height: 10px;
        border-radius: 50%;
        background: #ed8fbc;
        animation: sinyal-kedip 1.4s ease-in-out infinite;
    }

    /* BARU: caption di bawah strip */
    .alat-caption {
        margin-top: 8px;
        text-align: center;
        font-size: 12px;
        letter-spacing: .4px;
        color: #8a7a82;
    }

    @keyframes alat-melayang {
        0%, 100% { transform: translateY(-6px); }
        50%      { transform: translateY(6px); }
    }
    @keyframes kabel-jalan {
        from { background-position: 0 0; }
        to   { background-position: 26px 0; }
    }
    @keyframes sinyal-kedip {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%      { opacity: .25; transform: scale(1.5); }
    }

    @media (max-width: 575.98px) {
        .alat-item:nth-child(n+5) { display: none; }
        .alat-item:nth-child(1) { left: 6%; }
        .alat-item:nth-child(2) { left: 30%; }
        .alat-item:nth-child(3) { left: 54%; }
        .alat-item:nth-child(4) { left: 78%; }
    }

    @media (prefers-reduced-motion: reduce) {
        .alat-item, .alat-deco::before, .alat-item.sinyal::after { animation: none; }
    }
</style>

<div class="alat-deco" aria-hidden="true">
    <div class="alat-item">💻</div>
    <div class="alat-item">🖧</div>
    <div class="alat-item sinyal">📡</div>
    <div class="alat-item">🖥️</div>
    <div class="alat-item">⌨️</div>
    <div class="alat-item">🔌</div>
</div>

<!-- BARU -->
<div class="alat-caption">💻 Komputer &bull; 🖧 Jaringan &bull; 📡 Perangkat Lab</div>