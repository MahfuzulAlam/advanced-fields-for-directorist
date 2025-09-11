jQuery(function ($) {

  // Helper: check if Google Places API is available
  function isGooglePlacesLoaded() {
    return (typeof google !== "undefined" && google.maps && google.maps.places);
  }

  // Init autocomplete for a single input
  function initAutocompleteForInput(input) {
    if (!input || !isGooglePlacesLoaded()) return;

    const opt = {
      types: ["geocode"],
      componentRestrictions: {
        country: directorist.restricted_countries,
      },
    };
    const options = directorist.countryRestriction ? opt : { types: [] };

    const autocomplete = new google.maps.places.Autocomplete(input, options);

    autocomplete.addListener("place_changed", function () {
      const place = autocomplete.getPlace();
      if (!place.place_id) return;

      const $wrapper = $(input).closest(".address_item");
      $wrapper.find(".google_addresses_lat").val(place.geometry?.location?.lat() || "");
      $wrapper.find(".google_addresses_lng").val(place.geometry?.location?.lng() || "");

      // Store full place info (if not already there)
      let $hiddenInput = $wrapper.find(".google_place");
      if (!$hiddenInput.length) {
        $hiddenInput = $('<input>', { type: 'hidden', class: 'google_place' }).appendTo($wrapper);
      }
      $hiddenInput.val(JSON.stringify({
        place_id: place.place_id,
        place_address: input.value,
      }));

      generateJson();
    });
  }

  // Init all autocompletes on page load
  function initAutocomplete() {
    document.querySelectorAll(".directorist-form-multi-address-field .google_addresses").forEach(function (input) {
      initAutocompleteForInput(input);
    });
  }

  // Random int generator
  function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
  }

  // Get current address count
  function getCurrentAddressCount() {
    return $(".directorist-form-multi-address-field .address_item").length;
  }

  // Get address limit from input field
  function getAddressLimit() {
    const limitInput = document.getElementById('addresses_limit');
    return limitInput ? parseInt(limitInput.value) || 0 : 0;
  }

  // Update add button visibility based on limit
  function updateAddButtonVisibility() {
    const currentCount = getCurrentAddressCount();
    const limit = getAddressLimit();
    const $addBtn = $(".directorist-form-multi-address-field .add_address_btn");
    
    if (limit > 0 && currentCount >= limit) {
      $addBtn.hide();
    } else {
      $addBtn.show();
    }
  }

  // Check if labels are enabled
  function isLabelEnabled() {
    return $(".directorist-form-multi-address-field .address_label").length > 0;
  }

  // Generate new address field
  function generateAddressField() {
    const currentCount = getCurrentAddressCount();
    const limit = getAddressLimit();
    
    // Check if limit is reached
    if (limit > 0 && currentCount >= limit) {
      return; // Don't add more addresses if limit is reached
    }
    
    const uniqueId = getRandomInt(100000, 999999);
    const labelField = isLabelEnabled() ? 
      `<input type="text" autocomplete="off" name="address_labels[]" 
               class="directorist-form-element address_label" 
               placeholder="Enter label (e.g., Main Branch)">` : '';
    
    const newField = `
      <div class="address_item" data-id="${uniqueId}">
        ${labelField}
        <input type="text" autocomplete="off" name="addresses[]" 
               class="directorist-form-element google_addresses" 
               placeholder="Enter address">
        <input type="hidden" class="google_addresses_lat" name="latitude[]" value="">
        <input type="hidden" class="google_addresses_lng" name="longitude[]" value="">
        <button type="button" class="remove_address_btn">X</button>
      </div>
    `;
    $(".directorist-form-multi-address-field .address_field_holder").append(newField);
    
    // Update button visibility after adding
    updateAddButtonVisibility();
  }

  // Generate JSON from all fields
  function generateJson() {
    const addresses = [];
    $(".address_item").each(function () {
      const addr = $(this).find(".google_addresses").val() || "";
      const lat = $(this).find(".google_addresses_lat").val() || "";
      const lng = $(this).find(".google_addresses_lng").val() || "";
      const label = $(this).find(".address_label").val() || "";

      if (addr.trim() !== "") {
        const addressData = { address: addr, latitude: lat, longitude: lng };
        if (label.trim() !== "") {
          addressData.label = label;
        }
        addresses.push(addressData);
      }
    });

    $('input.google_addresses_json').val(JSON.stringify(addresses));
  }

  // Events
  $(document).on("click", ".directorist-form-multi-address-field .add_address_btn", function () {
    generateAddressField();
    const $newField = $(".directorist-form-multi-address-field .address_field_holder .address_item").last().find(".google_addresses");
    if ($newField.length) {
      initAutocompleteForInput($newField[0]);
    }
  });

  $(document).on("click", ".directorist-form-multi-address-field .remove_address_btn", function () {
    $(this).closest(".address_item").remove();
    generateJson();
    updateAddButtonVisibility(); // Update button visibility after removing
  });

  // Update JSON when label input changes
  $(document).on("input", ".directorist-form-multi-address-field .address_label", function () {
    generateJson();
  });

  // Init autocomplete after window load
  $(window).on("load", function () {
    if (isGooglePlacesLoaded()) {
      // take some time
      setTimeout(initAutocomplete, 1000);
      //initAutocomplete();
    } else {
      console.error("Google Maps JS API not loaded!");
    }
    
    // Update add button visibility on page load
    updateAddButtonVisibility();
  });

});