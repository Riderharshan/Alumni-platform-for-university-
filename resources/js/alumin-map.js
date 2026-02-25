import L from 'leaflet';
function createAvatarIcon(p) {
const html = `
<div class="relative -translate-y-2">
<div style="width:44px;height:44px;border-radius:50%;overflow:hidden;border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.35)">
<img src="${p.avatar ?? ''}" alt="${p.name ?? ''}" style="width:100%;height:100%;object-fit:cover;"/>
</div>
<div style="background:rgba(255,255,255,.95);padding:2px 6px;border-radius:12px;margin-top:4px;white-space:nowrap;font-size:12px;text-align:center;border:1px solid #e5e7eb;">
${p.name ?? ''}
</div>
</div>`;


return L.divIcon({
html,
className: 'alumni-marker',
iconSize: [44, 52],
iconAnchor: [22, 44],
popupAnchor: [0, -40],
});
}


function boot() {
const points = window.__alumniPoints || [];
const el = document.getElementById('alumni-map');
if (!el) return;


const map = L.map(el, { scrollWheelZoom: true });


L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
maxZoom: 19,
attribution: '&copy; OpenStreetMap contributors',
}).addTo(map);


if (!points.length) {
// Default to India view if no markers
map.setView([20.5937, 78.9629], 4);
return;
}


const markers = [];
const bounds = L.latLngBounds();


points.forEach((p) => {
if (typeof p.lat !== 'number' || typeof p.lng !== 'number') return;


const marker = L.marker([p.lat, p.lng], { icon: createAvatarIcon(p) })
.bindPopup(`
<div style="display:flex;gap:10px;align-items:center;">
<img src="${p.avatar ?? ''}" alt="${p.name ?? ''}" style="width:48px;height:48px;border-radius:50%;object-fit:cover;"/>
<div>
<div style="font-weight:600">${p.name ?? ''}</div>
${p.profile_url ? `<a href="${p.profile_url}">View profile</a>` : ''}
</div>
</div>
`);


marker.addTo(map);
markers.push(marker);
bounds.extend(marker.getLatLng());
});


if (markers.length === 1) {
map.setView(markers[0].getLatLng(), 13);
} else {
map.fitBounds(bounds.pad(0.2));
}
}


// Boot when data is ready (triggered from Blade)
if (document.readyState === 'loading') {
document.addEventListener('alumni-map-data-ready', boot);
} else {
boot();
}