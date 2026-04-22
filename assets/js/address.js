jQuery(function ($) {
  function isGooglePlacesLoaded() {
    return typeof google !== "undefined" && google.maps && google.maps.places;
  }

  function getFieldInstance($element) {
    return $element.closest(".directorist-form-multi-address-field");
  }

  function getAddressLimit($field) {
    return parseInt($field.attr("data-address-limit"), 10) || parseInt($field.find(".addresses_limit").val(), 10) || 0;
  }

  function hasLabelField($field) {
    return $field.attr("data-has-label") === "1";
  }

  function formatIndex(index) {
    return String(index + 1).padStart(2, "0");
  }

  function clearRowCoordinates($row) {
    $row.find(".google_addresses_lat, .google_addresses_lng").val("");
  }

  function updateAddButtonVisibility($field) {
    const limit = getAddressLimit($field);
    const count = $field.find(".address_item").length;
    const $button = $field.find(".add_address_btn");

    if (limit > 0 && count >= limit) {
      $button.hide();
      return;
    }

    $button.show();
  }

  function syncIndices($field) {
    const $rows = $field.find(".address_item");
    const shouldHideRemove = $rows.length === 1;

    $rows.each(function (index) {
      const $row = $(this);
      $row.attr("data-index", index);
      $row.find(".address_item__index").text(formatIndex(index));
      $row.find(".remove_address_btn").toggleClass("is-hidden", shouldHideRemove);
    });
  }

  function generateJson($field) {
    const addresses = [];

    $field.find(".address_item").each(function () {
      const $row = $(this);
      const address = ($row.find(".google_addresses").val() || "").trim();
      const latitude = ($row.find(".google_addresses_lat").val() || "").trim();
      const longitude = ($row.find(".google_addresses_lng").val() || "").trim();
      const label = ($row.find(".address_label").val() || "").trim();

      if (!address) {
        return;
      }

      const entry = {
        address: address,
        latitude: latitude,
        longitude: longitude,
      };

      if (label) {
        entry.label = label;
      }

      addresses.push(entry);
    });

    $field.find(".google_addresses_json").val(JSON.stringify(addresses));
  }

  function buildAddressRow($field) {
    const hasLabel = hasLabelField($field);
    const labelField = hasLabel
      ? `
          <div class="address_item__input-group">
            <label class="address_item__input-label">Label</label>
            <input
              type="text"
              autocomplete="off"
              name="address_labels[]"
              class="directorist-form-element address_label"
              placeholder="Main Branch"
            >
          </div>
        `
      : "";

    return `
      <div class="address_item" data-index="">
        <div class="address_item__index">00</div>
        <div class="address_item__content">
          ${labelField}
          <div class="address_item__input-group address_item__input-group--address">
            <label class="address_item__input-label">Address</label>
            <input
              type="text"
              autocomplete="street-address"
              name="addresses[]"
              class="directorist-form-element google_addresses"
              placeholder="Search for a location"
            >
          </div>
        </div>
        <input type="hidden" class="google_addresses_lat" name="latitude[]" value="">
        <input type="hidden" class="google_addresses_lng" name="longitude[]" value="">
        <button type="button" class="remove_address_btn" aria-label="Remove location">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    `;
  }

  function initAutocompleteForInput(input) {
    if (!input || input.dataset.autocompleteReady === "1" || !isGooglePlacesLoaded()) {
      return;
    }

    const restrictedCountries = window.directorist && directorist.restricted_countries ? directorist.restricted_countries : null;
    const options =
      window.directorist && directorist.countryRestriction && restrictedCountries
        ? {
            types: ["geocode"],
            componentRestrictions: {
              country: restrictedCountries,
            },
          }
        : {
            types: ["geocode"],
          };

    const autocomplete = new google.maps.places.Autocomplete(input, options);
    input.dataset.autocompleteReady = "1";
    input.dataset.selectedAddress = (input.value || "").trim();

    autocomplete.addListener("place_changed", function () {
      const place = autocomplete.getPlace();
      const $row = $(input).closest(".address_item");
      const $field = getFieldInstance($row);

      if (place.geometry && place.geometry.location) {
        $row.find(".google_addresses_lat").val(place.geometry.location.lat());
        $row.find(".google_addresses_lng").val(place.geometry.location.lng());
      } else {
        clearRowCoordinates($row);
      }

      input.dataset.selectedAddress = (input.value || "").trim();
      generateJson($field);
    });
  }

  function initAutocomplete($context) {
    if (!isGooglePlacesLoaded()) {
      return;
    }

    $context.find(".google_addresses").each(function () {
      initAutocompleteForInput(this);
    });
  }

  function initField($field) {
    $field.find(".google_addresses").each(function () {
      this.dataset.selectedAddress = (this.value || "").trim();
    });

    syncIndices($field);
    updateAddButtonVisibility($field);
    generateJson($field);
    initAutocomplete($field);
  }

  function initializeAllFields() {
    $(".directorist-form-multi-address-field").each(function () {
      initField($(this));
    });
  }

  function scheduleAutocompleteInit(attempts) {
    if (isGooglePlacesLoaded()) {
      initializeAllFields();
      return;
    }

    if (attempts <= 0) {
      return;
    }

    window.setTimeout(function () {
      scheduleAutocompleteInit(attempts - 1);
    }, 500);
  }

  $(document).on("click", ".directorist-form-multi-address-field .add_address_btn", function () {
    const $field = getFieldInstance($(this));
    const limit = getAddressLimit($field);
    const count = $field.find(".address_item").length;

    if (limit > 0 && count >= limit) {
      return;
    }

    const $row = $(buildAddressRow($field));
    $field.find(".address_field_holder").append($row);

    syncIndices($field);
    updateAddButtonVisibility($field);
    initAutocomplete($row);
    generateJson($field);

    const input = $row.find(".google_addresses").get(0);
    if (input) {
      input.focus();
    }
  });

  $(document).on("click", ".directorist-form-multi-address-field .remove_address_btn", function () {
    const $button = $(this);
    const $field = getFieldInstance($button);
    const $row = $button.closest(".address_item");

    if ($field.find(".address_item").length === 1) {
      $row.find("input[type='text']").val("");
      clearRowCoordinates($row);

      const input = $row.find(".google_addresses").get(0);
      if (input) {
        input.dataset.selectedAddress = "";
      }
    } else {
      $row.remove();
    }

    syncIndices($field);
    updateAddButtonVisibility($field);
    generateJson($field);
  });

  $(document).on("input", ".directorist-form-multi-address-field .address_label", function () {
    generateJson(getFieldInstance($(this)));
  });

  $(document).on("input", ".directorist-form-multi-address-field .google_addresses", function () {
    const input = this;
    const $input = $(input);
    const $field = getFieldInstance($input);
    const $row = $input.closest(".address_item");
    const currentValue = ($input.val() || "").trim();

    if ((input.dataset.selectedAddress || "") !== currentValue) {
      clearRowCoordinates($row);
    }

    generateJson($field);
  });

  $(document).on("change", ".directorist-form-multi-address-field .google_addresses_lat, .directorist-form-multi-address-field .google_addresses_lng", function () {
    generateJson(getFieldInstance($(this)));
  });

  $(document).on("submit", "form", function () {
    $(".directorist-form-multi-address-field").each(function () {
      generateJson($(this));
    });
  });

  initializeAllFields();
  $(window).on("load", function () {
    scheduleAutocompleteInit(12);
  });
});
