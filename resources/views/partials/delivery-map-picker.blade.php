@once
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIINfQnCv1xYw7ROtZ0qdhxYSo9PCHZy8C8=" crossorigin="">
<style>
  .delivery-map-picker { border: 1px solid #e2e5e9; border-radius: 8px; padding: 12px; background: #fbfbfc; }
  .delivery-map-panel { display: none; margin-top: 12px; }
  .delivery-map-canvas { width: 100%; height: 260px; border: 1px solid #d9dde3; border-radius: 8px; overflow: hidden; }
  .delivery-map-actions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
  .delivery-map-note { font-size: 12px; color: #6c757d; margin-top: 8px; }
  .delivery-map-canvas.leaflet-container {
    position: relative !important;
    overflow: hidden !important;
    background: #ddd !important;
    outline-offset: 1px;
  }
  .delivery-map-canvas .leaflet-pane,
  .delivery-map-canvas .leaflet-tile,
  .delivery-map-canvas .leaflet-marker-icon,
  .delivery-map-canvas .leaflet-marker-shadow,
  .delivery-map-canvas .leaflet-tile-container,
  .delivery-map-canvas .leaflet-pane > svg,
  .delivery-map-canvas .leaflet-pane > canvas,
  .delivery-map-canvas .leaflet-zoom-box,
  .delivery-map-canvas .leaflet-image-layer,
  .delivery-map-canvas .leaflet-layer {
    position: absolute !important;
    left: 0;
    top: 0;
  }
  .delivery-map-canvas .leaflet-tile-pane { z-index: 200; }
  .delivery-map-canvas .leaflet-overlay-pane { z-index: 400; }
  .delivery-map-canvas .leaflet-shadow-pane { z-index: 500; }
  .delivery-map-canvas .leaflet-marker-pane { z-index: 600; }
  .delivery-map-canvas .leaflet-tooltip-pane { z-index: 650; }
  .delivery-map-canvas .leaflet-popup-pane { z-index: 700; }
  .delivery-map-canvas .leaflet-control-container {
    position: absolute;
    inset: 0;
    z-index: 800;
    pointer-events: none;
  }
  .delivery-map-canvas .leaflet-control {
    position: relative;
    z-index: 800;
    pointer-events: auto;
    float: left;
    clear: both;
  }
  .delivery-map-canvas .leaflet-top,
  .delivery-map-canvas .leaflet-bottom {
    position: absolute;
    z-index: 1000;
    pointer-events: none;
  }
  .delivery-map-canvas .leaflet-top { top: 0; }
  .delivery-map-canvas .leaflet-bottom { bottom: 0; }
  .delivery-map-canvas .leaflet-left { left: 0; }
  .delivery-map-canvas .leaflet-right { right: 0; }
  .delivery-map-canvas .leaflet-control-zoom {
    margin: 10px 0 0 10px !important;
    border: 1px solid rgba(0, 0, 0, .2) !important;
    border-radius: 6px !important;
    overflow: hidden;
    background: #fff !important;
    box-shadow: 0 2px 7px rgba(0, 0, 0, .18);
  }
  .delivery-map-canvas .leaflet-control-zoom a {
    display: block !important;
    width: 32px !important;
    height: 32px !important;
    line-height: 30px !important;
    padding: 0 !important;
    margin: 0 !important;
    border: 0 !important;
    border-bottom: 1px solid #d9dde3 !important;
    background: #fff !important;
    color: #1f2937 !important;
    font-size: 22px !important;
    font-weight: 700 !important;
    text-align: center !important;
    text-decoration: none !important;
    text-indent: 0 !important;
    opacity: 1 !important;
  }
  .delivery-map-canvas .leaflet-control-zoom a:last-child {
    border-bottom: 0 !important;
  }
  .delivery-map-canvas .leaflet-control-zoom a:hover,
  .delivery-map-canvas .leaflet-control-zoom a:focus {
    background: #f3f6fb !important;
    color: #0d6efd !important;
  }
  .delivery-map-canvas .leaflet-tile,
  .delivery-map-canvas .leaflet-marker-icon,
  .delivery-map-canvas .leaflet-marker-shadow {
    max-width: none !important;
    max-height: none !important;
    object-fit: initial !important;
  }
  .delivery-map-canvas .leaflet-tile {
    width: 256px !important;
    height: 256px !important;
    border: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
  }
  .delivery-map-marker {
    width: 24px !important;
    height: 24px !important;
    margin-left: -12px !important;
    margin-top: -24px !important;
    border: 0 !important;
    background: transparent !important;
  }
  .delivery-map-marker::before {
    content: "";
    position: absolute;
    width: 22px;
    height: 22px;
    left: 1px;
    top: 0;
    background: #0d6efd;
    border: 2px solid #fff;
    border-radius: 50% 50% 50% 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, .35);
    transform: rotate(-45deg);
  }
  .delivery-map-marker::after {
    content: "";
    position: absolute;
    width: 8px;
    height: 8px;
    left: 8px;
    top: 7px;
    background: #fff;
    border-radius: 50%;
  }
  @media (max-width: 576px) {
    .delivery-map-canvas { height: 220px; }
  }
</style>
@endonce

<div class="delivery-map-picker mb-3" data-delivery-map-picker>
    <button type="button" class="btn btn-outline-secondary btn-sm" data-delivery-map-toggle aria-expanded="false">
        <i class="bi bi-geo-alt"></i> {{ __('messages.mapLocation') }}
    </button>

    <div class="delivery-map-panel" data-delivery-map-panel>
        <div class="delivery-map-canvas" data-delivery-map-canvas></div>
        <div class="delivery-map-actions">
            <button type="button" class="btn btn-outline-primary btn-sm" data-delivery-current-location>
                <i class="bi bi-crosshair"></i> {{ __('messages.useMyLocation') }}
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm" data-delivery-map-clear>
                <i class="bi bi-x-circle"></i> {{ __('messages.clearMapLocation') }}
            </button>
        </div>
        <div class="delivery-map-note">{{ __('messages.mapLocationHint') }}</div>
    </div>

    <input type="hidden" name="delivery_latitude" data-delivery-latitude>
    <input type="hidden" name="delivery_longitude" data-delivery-longitude>
</div>

@once
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const defaultCenter = [41.7151, 44.8271];
    const deliveryMarkerIcon = typeof L !== 'undefined'
        ? L.divIcon({ className: 'delivery-map-marker', iconSize: [24, 24], iconAnchor: [12, 24] })
        : null;

    document.querySelectorAll('[data-delivery-map-picker]').forEach(function (picker) {
        const toggle = picker.querySelector('[data-delivery-map-toggle]');
        const panel = picker.querySelector('[data-delivery-map-panel]');
        const canvas = picker.querySelector('[data-delivery-map-canvas]');
        const latInput = picker.querySelector('[data-delivery-latitude]');
        const lngInput = picker.querySelector('[data-delivery-longitude]');
        const clearButton = picker.querySelector('[data-delivery-map-clear]');
        const locationButton = picker.querySelector('[data-delivery-current-location]');
        let map = null;
        let marker = null;

        function setPoint(latlng) {
            const lat = Number(latlng.lat).toFixed(7);
            const lng = Number(latlng.lng).toFixed(7);
            latInput.value = lat;
            lngInput.value = lng;

            if (!marker) {
                marker = L.marker([lat, lng], { draggable: true, icon: deliveryMarkerIcon }).addTo(map);
                marker.on('dragend', function () {
                    setPoint(marker.getLatLng());
                });
            } else {
                marker.setLatLng([lat, lng]);
            }
        }

        function initMap() {
            if (map || typeof L === 'undefined') {
                return;
            }

            map = L.map(canvas).setView(defaultCenter, 12);
            const tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            tileLayer.on('load', refreshMapSize);

            map.on('click', function (event) {
                setPoint(event.latlng);
            });
        }

        function refreshMapSize() {
            if (!map) {
                return;
            }

            map.invalidateSize();
            requestAnimationFrame(function () {
                map.invalidateSize();
            });
            setTimeout(function () {
                map.invalidateSize();
            }, 150);
            setTimeout(function () {
                map.invalidateSize();
            }, 400);
        }

        toggle.addEventListener('click', function () {
            const isOpen = panel.style.display === 'block';
            panel.style.display = isOpen ? 'none' : 'block';
            toggle.setAttribute('aria-expanded', String(!isOpen));

            if (!isOpen) {
                initMap();
                refreshMapSize();
            }
        });

        clearButton.addEventListener('click', function () {
            latInput.value = '';
            lngInput.value = '';
            if (marker) {
                marker.remove();
                marker = null;
            }
        });

        locationButton.addEventListener('click', function () {
            if (!navigator.geolocation) {
                return;
            }

            navigator.geolocation.getCurrentPosition(function (position) {
                initMap();
                const point = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                map.setView([point.lat, point.lng], 16);
                setPoint(point);
            });
        });
    });
});
</script>
@endpush
@endonce
