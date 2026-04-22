jQuery(function ($) {
    function isGoogleMapsReady() {
        return (
            typeof google !== "undefined" &&
            google.maps &&
            typeof google.maps.Map === "function" &&
            typeof google.maps.InfoWindow === "function" &&
            typeof google.maps.LatLngBounds === "function"
        );
    }

    async function getAdvancedMarkerElement() {
        if (!isGoogleMapsReady()) {
            return null;
        }

        if (google.maps.marker && typeof google.maps.marker.AdvancedMarkerElement === "function") {
            return google.maps.marker.AdvancedMarkerElement;
        }

        if (typeof google.maps.importLibrary === "function") {
            const markerLibrary = await google.maps.importLibrary("marker");
            if (markerLibrary && typeof markerLibrary.AdvancedMarkerElement === "function") {
                return markerLibrary.AdvancedMarkerElement;
            }
        }

        return null;
    }

    function escapeHtml(value) {
        return String(value || "")
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function getAddressCards(mapContainer) {
        const wrapper = mapContainer.closest(".directorist-single-info__addresses");
        if (!wrapper) {
            return [];
        }

        return Array.from(wrapper.querySelectorAll(".addresses-list .address-item")).filter((item) => {
            const lat = parseFloat(item.dataset.lat);
            const lng = parseFloat(item.dataset.lng);

            return !Number.isNaN(lat) && !Number.isNaN(lng) && lat !== 0 && lng !== 0;
        });
    }

    function buildMarkerSvg(index, isActive) {
        const startColor = isActive ? "#0f766e" : "#2563eb";
        const endColor = isActive ? "#155e75" : "#1d4ed8";
        const number = escapeHtml(index + 1);

        return `
            <svg xmlns="http://www.w3.org/2000/svg" width="42" height="54" viewBox="0 0 42 54" fill="none">
                <defs>
                    <linearGradient id="dafMarkerGradient${index}" x1="4" y1="4" x2="38" y2="44" gradientUnits="userSpaceOnUse">
                        <stop stop-color="${startColor}" />
                        <stop offset="1" stop-color="${endColor}" />
                    </linearGradient>
                </defs>
                <path d="M21 52C21 52 38 34.87 38 21C38 11.6112 30.3888 4 21 4C11.6112 4 4 11.6112 4 21C4 34.87 21 52 21 52Z" fill="url(#dafMarkerGradient${index})" stroke="white" stroke-width="3"/>
                <circle cx="21" cy="21" r="9" fill="white" fill-opacity="0.18"/>
                <text x="21" y="25" text-anchor="middle" font-family="Arial, sans-serif" font-size="12" font-weight="700" fill="white">${number}</text>
            </svg>
        `;
    }

    function createMarkerContent(index, isActive) {
        const markerElement = document.createElement("div");
        markerElement.className = "daf-google-marker" + (isActive ? " is-active" : "");
        markerElement.innerHTML = buildMarkerSvg(index, isActive);
        return markerElement;
    }

    function buildPopupContent(card) {
        const title = escapeHtml(card.dataset.title || "Location");
        const address = escapeHtml(card.dataset.address || "");
        const mapUrl = escapeHtml(card.dataset.mapUrl || "");

        return `
            <div class="daf-map-popup">
                <span class="daf-map-popup__eyebrow">Selected location</span>
                <strong class="daf-map-popup__title">${title}</strong>
                ${address ? `<p class="daf-map-popup__text">${address}</p>` : ""}
                <a class="daf-map-popup__link" href="${mapUrl}" target="_blank" rel="noopener noreferrer">Open in Google Maps</a>
            </div>
        `;
    }

    function setActiveState(cards, markerNodes, markers, activeIndex) {
        cards.forEach((card, index) => {
            card.classList.toggle("active", index === activeIndex);
        });

        markers.forEach((marker, index) => {
            if (markerNodes[index]) {
                markerNodes[index].classList.toggle("is-active", index === activeIndex);
                markerNodes[index].innerHTML = buildMarkerSvg(index, index === activeIndex);
            }

            marker.zIndex = index === activeIndex ? 999 : index + 1;
        });
    }

    async function createGoogleMap(mapContainer) {
        if (!mapContainer || mapContainer.dataset.initialized === "1" || mapContainer.dataset.initializing === "1") {
            return;
        }

        if (!isGoogleMapsReady()) {
            return;
        }

        mapContainer.dataset.initializing = "1";

        const AdvancedMarkerElement = await getAdvancedMarkerElement();
        if (!AdvancedMarkerElement) {
            delete mapContainer.dataset.initializing;
            return;
        }

        const cards = getAddressCards(mapContainer);
        if (!cards.length) {
            delete mapContainer.dataset.initializing;
            return;
        }

        const coordinates = cards.map((card) => ({
            lat: parseFloat(card.dataset.lat),
            lng: parseFloat(card.dataset.lng)
        }));

        const centerLat = coordinates.reduce((sum, point) => sum + point.lat, 0) / coordinates.length;
        const centerLng = coordinates.reduce((sum, point) => sum + point.lng, 0) / coordinates.length;

        const map = new google.maps.Map(mapContainer, {
            zoom: 13,
            center: { lat: centerLat, lng: centerLng },
            mapTypeId: "roadmap",
            mapId: "single_listing_map",
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
            gestureHandling: "cooperative"
        });

        mapContainer.dataset.initialized = "1";
        delete mapContainer.dataset.initializing;

        const infoWindow = new google.maps.InfoWindow();
        const markerNodes = cards.map((card, index) => createMarkerContent(index, index === 0));
        const markers = cards.map((card, index) => {
            return new AdvancedMarkerElement({
                position: {
                    lat: parseFloat(card.dataset.lat),
                    lng: parseFloat(card.dataset.lng)
                },
                map: map,
                title: card.dataset.title || `Location ${index + 1}`,
                content: markerNodes[index],
                gmpClickable: true,
                zIndex: index === 0 ? 999 : index + 1
            });
        });

        function focusLocation(index, shouldOpenPopup) {
            const marker = markers[index];
            const point = coordinates[index];
            if (!marker || !point) {
                return;
            }

            setActiveState(cards, markerNodes, markers, index);
            map.panTo(point);
            map.setZoom(Math.max(map.getZoom(), 14));

            if (shouldOpenPopup) {
                infoWindow.setContent(buildPopupContent(cards[index]));
                infoWindow.open({
                    map: map,
                    anchor: marker
                });
            }
        }

        cards.forEach((card, index) => {
            card.addEventListener("click", function (event) {
                if (event.target.closest("a")) {
                    return;
                }

                focusLocation(index, true);
            });

            const focusButton = card.querySelector("[data-focus-map='true']");
            if (focusButton) {
                focusButton.addEventListener("click", function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    focusLocation(index, true);
                });
            }
        });

        markers.forEach((marker, index) => {
            marker.addListener("click", function () {
                setActiveState(cards, markerNodes, markers, index);
                infoWindow.setContent(buildPopupContent(cards[index]));
                infoWindow.open({
                    map: map,
                    anchor: marker
                });
            });
        });

        if (markers.length > 1) {
            const bounds = new google.maps.LatLngBounds();
            coordinates.forEach((point) => {
                bounds.extend(point);
            });
            map.fitBounds(bounds);
            setActiveState(cards, markerNodes, markers, 0);
        } else {
            focusLocation(0, false);
        }
    }

    function initGoogleMaps(context) {
        if (!isGoogleMapsReady()) {
            return;
        }

        const root = context || document;
        const containers = root.querySelectorAll ? root.querySelectorAll(".addresses-map[data-map-type='google']") : [];
        containers.forEach((container) => {
            createGoogleMap(container);
        });
    }

    function scheduleInit(attempts) {
        if (isGoogleMapsReady()) {
            initGoogleMaps(document);
            return;
        }

        if (attempts <= 0) {
            return;
        }

        window.setTimeout(function () {
            scheduleInit(attempts - 1);
        }, 500);
    }

    initGoogleMaps(document);
    $(window).on("load", function () {
        scheduleInit(12);
    });

    if (window.MutationObserver) {
        const observer = new MutationObserver((mutations) => {
            for (const mutation of mutations) {
                for (const node of mutation.addedNodes) {
                    if (!(node instanceof Element)) {
                        continue;
                    }

                    if (node.matches(".addresses-map[data-map-type='google']") || node.querySelector(".addresses-map[data-map-type='google']")) {
                        initGoogleMaps(document);
                        return;
                    }
                }
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
});
