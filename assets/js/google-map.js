jQuery(function ($) {
    
    // Initialize Google Maps for addresses
    function initGoogleMaps() {
        console.log('Initializing Google Maps...');
        
        const mapContainer = document.getElementById('addresses-map');
        if (!mapContainer) {
            console.log('Map container not found');
            return;
        }

        // Get all address items with coordinates
        const addressItems = document.querySelectorAll('.addresses-list .address-item[data-lat][data-lng]');
        console.log('Found address items:', addressItems.length);
        
        if (addressItems.length === 0) {
            console.log('No address items with coordinates found');
            return;
        }

        // Debug: Log coordinates
        addressItems.forEach((item, index) => {
            console.log(`Address ${index + 1}:`, {
                lat: item.dataset.lat,
                lng: item.dataset.lng,
                label: item.dataset.label
            });
        });

        // Check if Google Maps API is loaded
        if (typeof google === 'undefined' || !google.maps) {
            console.error('Google Maps API not loaded');
            return;
        }

        createGoogleMap(addressItems);
    }

    // Create Google Map with markers
    function createGoogleMap(addressItems) {
        console.log('Creating Google Map with', addressItems.length, 'address items');
        
        const coordinates = [];
        const markers = [];

        // Collect all coordinates
        addressItems.forEach((item, index) => {
            const lat = parseFloat(item.dataset.lat);
            const lng = parseFloat(item.dataset.lng);
            const label = item.dataset.label || `Address ${index + 1}`;

            console.log(`Processing address ${index + 1}:`, { lat, lng, label });

            if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                coordinates.push({
                    lat: lat,
                    lng: lng,
                    label: label,
                    element: item
                });
                console.log(`Valid coordinates added: [${lat}, ${lng}]`);
            } else {
                console.warn(`Invalid coordinates for address ${index + 1}: lat=${lat}, lng=${lng}`);
            }
        });

        console.log('Valid coordinates found:', coordinates.length);

        if (coordinates.length === 0) {
            console.error('No valid coordinates found for map creation');
            return;
        }

        // Calculate center point
        const centerLat = coordinates.reduce((sum, coord) => sum + coord.lat, 0) / coordinates.length;
        const centerLng = coordinates.reduce((sum, coord) => sum + coord.lng, 0) / coordinates.length;

        console.log('Map center:', { centerLat, centerLng });

        // Create map
        const map = new google.maps.Map(document.getElementById('addresses-map'), {
            zoom: 13,
            center: { lat: centerLat, lng: centerLng },
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [
                {
                    featureType: 'poi',
                    elementType: 'labels',
                    stylers: [{ visibility: 'on' }]
                }
            ]
        });
        console.log('Google Map created successfully');

        // Create info window
        const infoWindow = new google.maps.InfoWindow();

        // Add markers for each address
        coordinates.forEach((coord, index) => {
            console.log(`Adding marker ${index + 1}:`, { lat: coord.lat, lng: coord.lng, label: coord.label });

            const marker = new google.maps.Marker({
                position: { lat: coord.lat, lng: coord.lng },
                map: map,
                title: coord.label,
                animation: google.maps.Animation.DROP
            });

            console.log(`Marker ${index + 1} added to map`);

            // Create info window content
            const infoContent = `
                <div class="map-popup">
                    <strong>${coord.label}</strong>
                    <br>
                    <a href="https://www.google.com/maps?q=${coord.lat},${coord.lng}" target="_blank" style="color: #3b82f6; text-decoration: none;">
                        View on Google Maps
                    </a>
                </div>
            `;

            // Add click event to marker
            marker.addListener('click', function() {
                console.log(`Marker ${index + 1} clicked`);
                infoWindow.setContent(infoContent);
                infoWindow.open(map, marker);
                
                // Highlight corresponding address item
                addressItems.forEach(i => i.classList.remove('active'));
                coord.element.classList.add('active');
            });

            markers.push(marker);

            // Add click event to address item
            coord.element.addEventListener('click', function() {
                console.log(`Address item ${index + 1} clicked`);
                // Remove active class from all items
                addressItems.forEach(i => i.classList.remove('active'));
                // Add active class to clicked item
                this.classList.add('active');
                
                // Center map on this marker
                map.setCenter({ lat: coord.lat, lng: coord.lng });
                map.setZoom(15);
                
                // Open info window
                infoWindow.setContent(infoContent);
                infoWindow.open(map, marker);
            });
        });

        // Fit map to show all markers if there are multiple
        if (markers.length > 1) {
            const bounds = new google.maps.LatLngBounds();
            markers.forEach(marker => {
                bounds.extend(marker.getPosition());
            });
            map.fitBounds(bounds);
            console.log('Map bounds fitted to show all markers');
        } else if (markers.length === 1) {
            console.log('Single marker, using default zoom');
        }

        console.log('Total markers created:', markers.length);
    }

    // Initialize map when DOM is ready
    $(document).ready(function() {
        console.log('Document ready, initializing Google Map...');
        // Wait for Google Maps API to be loaded
        if (typeof google !== 'undefined' && google.maps) {
            setTimeout(initGoogleMaps, 1000);
        } else {
            // Wait for Google Maps API to load
            const checkGoogleMaps = setInterval(function() {
                if (typeof google !== 'undefined' && google.maps) {
                    clearInterval(checkGoogleMaps);
                    setTimeout(initGoogleMaps, 1000);
                }
            }, 100);
            
            // Stop checking after 10 seconds
            setTimeout(function() {
                clearInterval(checkGoogleMaps);
            }, 10000);
        }
    });

    // Also try to initialize on window load
    $(window).on('load', function() {
        console.log('Window loaded, checking for Google Map...');
        if (typeof google !== 'undefined' && google.maps) {
            setTimeout(initGoogleMaps, 500);
        }
    });

    // Re-initialize if content is dynamically loaded
    $(document).on('DOMNodeInserted', function() {
        if (document.getElementById('addresses-map') && typeof google !== 'undefined' && google.maps) {
            console.log('Dynamic content detected, re-initializing Google Map...');
            setTimeout(initGoogleMaps, 500);
        }
    });

});