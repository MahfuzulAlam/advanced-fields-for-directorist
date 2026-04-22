jQuery(function ($) {
    let leafletLoadPromise;

    function escapeHtml(value) {
        return String(value || "")
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function loadLeafletResources() {
        if (typeof L !== "undefined") {
            return Promise.resolve(window.L);
        }

        if (leafletLoadPromise) {
            return leafletLoadPromise;
        }

        leafletLoadPromise = new Promise((resolve, reject) => {
            const existingLeafletCss = document.querySelector('link[data-daf-leaflet="1"]');
            const existingLeafletScript = document.querySelector('script[data-daf-leaflet="1"]');

            if (!existingLeafletCss) {
                const cssLink = document.createElement("link");
                cssLink.rel = "stylesheet";
                cssLink.href = "https://unpkg.com/leaflet@1.9.4/dist/leaflet.css";
                cssLink.integrity = "sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=";
                cssLink.crossOrigin = "";
                cssLink.dataset.dafLeaflet = "1";
                document.head.appendChild(cssLink);
            }

            if (existingLeafletScript) {
                existingLeafletScript.addEventListener("load", () => resolve(window.L), { once: true });
                existingLeafletScript.addEventListener("error", reject, { once: true });
                return;
            }

            const script = document.createElement("script");
            script.src = "https://unpkg.com/leaflet@1.9.4/dist/leaflet.js";
            script.integrity = "sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=";
            script.crossOrigin = "";
            script.dataset.dafLeaflet = "1";
            script.onload = () => resolve(window.L);
            script.onerror = reject;
            document.head.appendChild(script);
        });

        return leafletLoadPromise;
    }

    function getAddressCards(mapContainer) {
        const wrapper = mapContainer.closest(".directorist-single-info__addresses");
        if (!wrapper) {
            return [];
        }

        return Array.from(wrapper.querySelectorAll(".addresses-list .address-item")).filter((item) => {
            const lat = parseFloat(item.dataset.lat);
            const lng = parseFloat(item.dataset.lng);

            return !Number.isNaN(lat) && !Number.isNaN(lng);
        });
    }

    function createMarkerIcon(index) {
        return L.divIcon({
            html: `
                <span class="daf-map-marker">
                    <span class="daf-map-marker__pulse"></span>
                    <span class="daf-map-marker__pin">${index + 1}</span>
                </span>
            `,
            iconSize: [38, 50],
            iconAnchor: [19, 44],
            popupAnchor: [0, -34],
            className: "daf-map-marker-icon"
        });
    }

    function buildPopupContent(item) {
        const title = escapeHtml(item.dataset.title || "Location");
        const address = escapeHtml(item.dataset.address || "");
        const mapUrl = escapeHtml(item.dataset.mapUrl || "");

        return `
            <div class="daf-map-popup">
                <span class="daf-map-popup__eyebrow">Selected location</span>
                <strong class="daf-map-popup__title">${title}</strong>
                ${address ? `<p class="daf-map-popup__text">${address}</p>` : ""}
                <a class="daf-map-popup__link" href="${mapUrl}" target="_blank" rel="noopener noreferrer">Open in Google Maps</a>
            </div>
        `;
    }

    function setActiveState(cards, markers, activeIndex) {
        cards.forEach((card, index) => {
            card.classList.toggle("active", index === activeIndex);
        });

        markers.forEach((marker, index) => {
            const markerElement = marker.getElement();
            if (!markerElement) {
                return;
            }

            const markerShell = markerElement.querySelector(".daf-map-marker");
            if (markerShell) {
                markerShell.classList.toggle("is-active", index === activeIndex);
            }
        });
    }

    function createMap(mapContainer) {
        if (!mapContainer || mapContainer.dataset.initialized === "1") {
            return;
        }

        const cards = getAddressCards(mapContainer);
        if (!cards.length) {
            return;
        }

        const coordinates = cards.map((card) => ({
            lat: parseFloat(card.dataset.lat),
            lng: parseFloat(card.dataset.lng),
            element: card
        }));

        const centerLat = coordinates.reduce((sum, point) => sum + point.lat, 0) / coordinates.length;
        const centerLng = coordinates.reduce((sum, point) => sum + point.lng, 0) / coordinates.length;

        const map = L.map(mapContainer, {
            scrollWheelZoom: false,
            zoomControl: true
        }).setView([centerLat, centerLng], 13);

        mapContainer.dataset.initialized = "1";

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        const markers = coordinates.map((point, index) => {
            const marker = L.marker([point.lat, point.lng], {
                icon: createMarkerIcon(index)
            }).addTo(map);

            marker.bindPopup(buildPopupContent(cards[index]));
            return marker;
        });

        function focusLocation(index, shouldOpenPopup) {
            const point = coordinates[index];
            if (!point) {
                return;
            }

            setActiveState(cards, markers, index);
            map.setView([point.lat, point.lng], Math.max(map.getZoom(), 14), {
                animate: true
            });

            if (shouldOpenPopup) {
                markers[index].openPopup();
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
            marker.on("click", function () {
                setActiveState(cards, markers, index);
            });
        });

        if (markers.length > 1) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.15));
            setActiveState(cards, markers, 0);
        } else {
            focusLocation(0, false);
        }

        window.setTimeout(function () {
            map.invalidateSize();
        }, 250);
    }

    function initAddressMaps(context) {
        const root = context || document;
        const containers = root.querySelectorAll ? root.querySelectorAll(".addresses-map[data-map-type='openstreet']") : [];

        if (!containers.length) {
            return;
        }

        loadLeafletResources()
            .then(() => {
                containers.forEach((container) => createMap(container));
            })
            .catch(() => {
                /* Leaflet failed to load. Keep the cards usable. */
            });
    }

    initAddressMaps(document);
    $(window).on("load", function () {
        initAddressMaps(document);
    });

    if (window.MutationObserver) {
        const observer = new MutationObserver((mutations) => {
            for (const mutation of mutations) {
                for (const node of mutation.addedNodes) {
                    if (!(node instanceof Element)) {
                        continue;
                    }

                    if (node.matches(".addresses-map[data-map-type='openstreet']") || node.querySelector(".addresses-map[data-map-type='openstreet']")) {
                        initAddressMaps(document);
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
