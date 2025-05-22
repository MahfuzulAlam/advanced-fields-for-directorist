function initAutocomplete() {
    const input = document.getElementsByClassName("directorist-address-js");
    new google.maps.places.Autocomplete(input, {
      types: ["geocode"], // restricts to geographical location types
      componentRestrictions: { country: directorist.restricted_countries } // optional
    });
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", initAutocomplete);