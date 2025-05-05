import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

window.addEventListener('DOMContentLoaded', () => {
    const map = L.map('map').setView([-45.578407, -72.068062], 14); // Coordenadas de Coyhaique

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    L.marker([-45.578407, -72.068062])
        .addTo(map)
        .bindPopup('Rent a Car Coyhaique')
        .openPopup();
});
import { Mail, Phone, MapPin } from 'lucide';
window.Lucide = { Mail, Phone, MapPin };
import { createIcons } from 'lucide';
createIcons();