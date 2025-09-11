jQuery(function ($) {
    
    // Initialize OpenStreetMap for addresses
    function initAddressesMap() {
        const mapContainer = document.getElementById('addresses-map');
        if (!mapContainer) return;

        // Get all address items with coordinates
        const addressItems = document.querySelectorAll('.addresses-list .address-item[data-lat][data-lng]');
        if (addressItems.length === 0) return;

        // Load Leaflet CSS and JS dynamically
        loadLeafletResources().then(() => {
            createMap(addressItems);
        }).catch(error => {
            console.error('Failed to load Leaflet resources:', error);
        });
    }

    // Load Leaflet CSS and JS
    function loadLeafletResources() {
        return new Promise((resolve, reject) => {
            // Check if Leaflet is already loaded
            if (typeof L !== 'undefined') {
                resolve();
                return;
            }

            // Load CSS
            const cssLink = document.createElement('link');
            cssLink.rel = 'stylesheet';
            cssLink.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
            cssLink.integrity = 'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=';
            cssLink.crossOrigin = '';
            document.head.appendChild(cssLink);

            // Load JS
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
            script.integrity = 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
            script.crossOrigin = '';
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }

    // Create the map with markers
    function createMap(addressItems) {
        const coordinates = [];
        const markers = [];

        // Collect all coordinates
        addressItems.forEach((item, index) => {
            const lat = parseFloat(item.dataset.lat);
            const lng = parseFloat(item.dataset.lng);
            const label = item.dataset.label || `Address ${index + 1}`;

            if (!isNaN(lat) && !isNaN(lng)) {
                coordinates.push([lat, lng]);
            }
        });

        if (coordinates.length === 0) return;

        // Calculate center point
        const centerLat = coordinates.reduce((sum, coord) => sum + coord[0], 0) / coordinates.length;
        const centerLng = coordinates.reduce((sum, coord) => sum + coord[1], 0) / coordinates.length;

        // Create map
        const map = L.map('addresses-map').setView([centerLat, centerLng], 13);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // Add markers for each address
        addressItems.forEach((item, index) => {
            const lat = parseFloat(item.dataset.lat);
            const lng = parseFloat(item.dataset.lng);
            const label = item.dataset.label || `Address ${index + 1}`;

            if (!isNaN(lat) && !isNaN(lng)) {
                var fontAwesomeIcon = L.divIcon({
                    html: "<div class=\"atbd_map_shape\"><span class=\"directorist-icon-mask\"></span></div>",
                    iconSize: [20, 20],
                    className: 'myDivIcon'
                });
                const marker = L.marker([lat, lng], {
                    icon: fontAwesomeIcon
                }).addTo(map);
                
                // Create popup content
                const popupContent = `
                    <div class="map-popup">
                        <strong>${label}</strong>
                        <br>
                        <a href="https://www.google.com/maps?q=${lat},${lng}" target="_blank" style="color: #3b82f6; text-decoration: none;">
                            View on Google Maps
                        </a>
                    </div>
                `;
                
                marker.bindPopup(popupContent);
                markers.push(marker);

                // Add click event to address item
                item.addEventListener('click', function() {
                    // Remove active class from all items
                    addressItems.forEach(i => i.classList.remove('active'));
                    // Add active class to clicked item
                    this.classList.add('active');
                    
                    // Center map on this marker
                    map.setView([lat, lng], 15);
                    marker.openPopup();
                });
            }
        });

        // Fit map to show all markers
        if (markers.length > 1) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        }

        // Add click event to markers to highlight corresponding address item
        markers.forEach((marker, index) => {
            marker.on('click', function() {
                addressItems.forEach(i => i.classList.remove('active'));
                if (addressItems[index]) {
                    addressItems[index].classList.add('active');
                }
            });
        });
    }

    // Initialize map when DOM is ready
    $(document).ready(function() {
        // Small delay to ensure all elements are rendered
        setTimeout(initAddressesMap, 500);
    });

    // Re-initialize if content is dynamically loaded
    $(document).on('DOMNodeInserted', function() {
        if (document.getElementById('addresses-map') && typeof L === 'undefined') {
            setTimeout(initAddressesMap, 500);
        }
    });

});
