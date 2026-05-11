@props(['lat' => 30.0444, 'lng' => 31.2357, 'zoom' => 13, 'markers' => []])

<div x-data="googleMapComponent({
        lat: {{ $lat }},
        lng: {{ $lng }},
        zoom: {{ $zoom }},
        markers: {{ json_encode($markers) }}
    })" 
    {{ $attributes->merge(['class' => 'w-full h-full relative rounded-xl shadow-lg border border-surface-200 z-10']) }}
>
    <div x-ref="map" class="absolute inset-0 w-full h-full rounded-xl"></div>
</div>

@once
@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('googleMapComponent', (config) => ({
            map: null,
            mapMarkers: [],
            
            init() {
                if (typeof google === 'object' && typeof google.maps === 'object') {
                    this.initMap();
                } else {
                    window.addEventListener('google-maps-loaded', () => this.initMap());
                }

                // Listen for custom events to update markers
                this.$el.addEventListener('update-markers', (e) => {
                    this.updateMarkers(e.detail);
                });
            },

            initMap() {
                this.map = new google.maps.Map(this.$refs.map, {
                    center: { lat: parseFloat(config.lat), lng: parseFloat(config.lng) },
                    zoom: config.zoom,
                    mapTypeControl: false,
                    streetViewControl: false,
                    fullscreenControl: true,
                });

                if (config.markers && config.markers.length > 0) {
                    this.updateMarkers(config.markers);
                }
            },

            updateMarkers(newMarkers) {
                if (!this.map) return;

                // Clear old markers
                this.mapMarkers.forEach(m => m.setMap(null));
                this.mapMarkers = [];

                let bounds = new google.maps.LatLngBounds();
                let hasValidMarkers = false;

                newMarkers.forEach(markerData => {
                    if (markerData.lat && markerData.lng) {
                        const position = { lat: parseFloat(markerData.lat), lng: parseFloat(markerData.lng) };
                        const markerObj = new google.maps.Marker({
                            position: position,
                            map: this.map,
                            title: markerData.title || '',
                            icon: markerData.icon || null
                        });
                        
                        if (markerData.infoWindow) {
                            const infoWindow = new google.maps.InfoWindow({ content: markerData.infoWindow });
                            markerObj.addListener('click', () => {
                                infoWindow.open(this.map, markerObj);
                            });
                        }

                        this.mapMarkers.push(markerObj);
                        bounds.extend(position);
                        hasValidMarkers = true;
                    }
                });

                if (hasValidMarkers && newMarkers.length > 1) {
                    this.map.fitBounds(bounds);
                    // Add padding to bounds
                    const listener = google.maps.event.addListener(this.map, 'idle', () => { 
                        if (this.map.getZoom() > 15) this.map.setZoom(15); 
                        google.maps.event.removeListener(listener); 
                    });
                } else if (hasValidMarkers && newMarkers.length === 1) {
                    this.map.setCenter(bounds.getCenter());
                    this.map.setZoom(15);
                }
            }
        }));
    });
</script>
@endpush
@endonce
