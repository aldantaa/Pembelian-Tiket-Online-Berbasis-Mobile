<div class="qr">
    <div class="qr__box">
        {!! QrCode::size(160)->margin(1)->generate($code) !!}
    </div>

    <p>Tunjukkan QR code ini saat boarding</p>
    <small>Kode booking: {{ $code }}</small>
</div>
