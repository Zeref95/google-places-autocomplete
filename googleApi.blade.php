<script defer
        src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&callback=initAutocomplete&libraries=places&v=weekly">
</script>
<script>
  let autocomplete = new Map();
  let address1Fields;
  let postalFields;

  function initAutocomplete() {
    document.addEventListener("DOMContentLoaded", function(event) {
      address1Fields = document.querySelectorAll("input[data-google-api]");
      address1Fields.forEach((el) => {

        let i = el.attributes['data-google-api'].value;

        autocomplete.set(i, new google.maps.places.Autocomplete(el, {
          componentRestrictions: { country: ["uk"] },
          fields: ["address_components", "geometry"],
          types: ["address"],
        }));

        autocomplete.get(i).addListener("place_changed", (id = i) => fillInAddress(id));
      })
    });
  }

  function fillInAddress(id) {
    const place = autocomplete.get(id).getPlace();
    let address1 = "";
    let postcode = "";

    if (!place) {
      return;
    }

    for (const component of place.address_components) {
      const componentType = component.types[0];

      switch (componentType) {
        case "street_number": {
          address1 = `${component.long_name} ${address1}`;
          break;
        }

        case "route": {
          address1 += component.short_name;
          break;
        }

        case "postal_code": {
          postcode = `${component.long_name}${postcode}`;
          break;
        }

        case "postal_code_suffix": {
          postcode = `${postcode}-${component.long_name}`;
          break;
        }
      }
    }

    //address1Field.value = address1;

    let postalField = document.querySelector(`input[data-google-api-postcode="${id}"]`)
    if (postalField) {
      postalField.value = postcode;
    }
  }
</script>