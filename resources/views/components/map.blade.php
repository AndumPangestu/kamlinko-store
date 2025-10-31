<div id="map"></div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
    #map {
        height: 20rem;
        width: 100%;
    }
</style>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>

    const map = L.map('map').setView([{{ $latitude }}, {{ $longitude }}], 19);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add a marker 
    const marker = L.marker([{{ $latitude }}, {{ $longitude }}]).addTo(map);
</script>
