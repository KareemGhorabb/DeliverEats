<div 
    wire:poll.10s 
    x-data="controlTowerMap(@js($initialData))"
    class="w-full relative"
>
    <div class="hidden" x-effect="updateMarkers($wire.getMapData)"></div>

    <div wire:ignore id="dispatch-map" class="w-full h-[700px] rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 z-10"></div>
</div>

@script
<script>
    Alpine.data('controlTowerMap', (initialData) => ({
        map: null,
        markers: [],
        
        init() {
            if (typeof google === 'object' && typeof google.maps === 'object') {
                this.initMap();
            } else {
                window.addEventListener('google-maps-loaded', () => this.initMap());
            }
        },

        initMap() {
            this.map = new google.maps.Map(document.getElementById('dispatch-map'), {
                center: { lat: 30.0444, lng: 31.2357 }, // Cairo
                zoom: 12,
                mapId: 'DEMO_MAP_ID', 
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
            });

            this.updateMarkers(initialData);
        },

        async updateMarkers(newData) {
            const data = await newData;
            if (!this.map || !data) return;

            // Clear old markers
            this.markers.forEach(marker => marker.setMap(null));
            this.markers = [];

            // Plot Riders (Green)
            data.riders.forEach(rider => {
                if(rider.latitude && rider.longitude) {
                    this.markers.push(new google.maps.Marker({
                        position: { lat: parseFloat(rider.latitude), lng: parseFloat(rider.longitude) },
                        map: this.map,
                        title: `Rider: ${rider.name}`,
                        icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
                    }));
                }
            });

            // Plot Restaurants (Blue)
            data.restaurants.forEach(rest => {
                if(rest.latitude && rest.longitude) {
                    this.markers.push(new google.maps.Marker({
                        position: { lat: parseFloat(rest.latitude), lng: parseFloat(rest.longitude) },
                        map: this.map,
                        title: `Restaurant: ${rest.name}`,
                        icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                    }));
                }
            });

            // Plot Orders (Red)
            data.orders.forEach(order => {
                if(order.lat && order.lng) {
                    this.markers.push(new google.maps.Marker({
                        position: { lat: parseFloat(order.lat), lng: parseFloat(order.lng) },
                        map: this.map,
                        title: `Order #${order.id} - ${order.status}`,
                        icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
                    }));
                }
            });
        }
    }));
</script>
@endscript
