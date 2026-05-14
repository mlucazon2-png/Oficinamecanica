@extends('layouts.app')
@section('title', 'Localização')
@section('breadcrumb', 'Localização')

@section('content')
<section class="home-location location-page" id="localizacao">
    <div class="home-location-copy">
        <span class="home-kicker">LOCALIZAÇÃO</span>
        <h2>CONHEÇA NOSSA LOCALIZAÇÃO</h2>
        <p>Rua João da Penha, 742, Fortaleza, CE, 60110-120. Abra o mapa para conferir a rota e chegar com tranquilidade.</p>
        <div class="home-location-actions">
            <a class="btn btn-primary" href="https://www.google.com/maps/search/?api=1&query=Rua%20Joao%20da%20Penha%20742%20Fortaleza%20CE" target="_blank" rel="noopener">
                <i class="bi bi-box-arrow-up-right me-1"></i>Abrir no Maps
            </a>
            <a class="btn btn-outline-secondary" href="https://www.google.com/maps/dir/?api=1&destination=Rua%20Joao%20da%20Penha%20742%20Fortaleza%20CE" target="_blank" rel="noopener">
                <i class="bi bi-signpost-split me-1"></i>Traçar rota
            </a>
        </div>
    </div>
    <div class="home-map-card">
        <iframe
            title="Localização AutoTech"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            src="https://www.google.com/maps?q=Rua%20Joao%20da%20Penha%20742%20Fortaleza%20CE&output=embed">
        </iframe>
    </div>
</section>
@endsection
