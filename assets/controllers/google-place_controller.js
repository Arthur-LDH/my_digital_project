import {Controller} from "@hotwired/stimulus";

export default class extends Controller {

    static values = {
        page: String,
        form: String
    }

    connect() {
        let page = this.pageValue;
        let form = this.formValue;
        console.log("page : " + page);
        console.log("form : " + form);
        // console.log(this.pageValue);
        let geoloc = {
            lat: null,
            lng: null,
        }
        const optionsGeoloc = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0,
        };

        function success(pos) {
            const crd = pos.coords;

            geoloc = {
                lat: crd.latitude,
                lng: crd.longitude,
            }
        }

        function error(err) {
            console.warn(`ERROR(${err.code}): ${err.message}`);
        }

        navigator.geolocation.getCurrentPosition(success, error, optionsGeoloc);
        // let address = <?php echo getAddress(); ?>;
        let center = null;
        if (geoloc.lat != null && geoloc.lng != null) {
            center = {lat: geoloc.lat, lng: geoloc.lng};
        }
        else {
            center = {lat: 47.658236, lng: -2.760847};
        }
        const input = document.getElementById("searchBarAutocomplete");

        let defaultBounds = {
            north: center.lat + 0.1,
            south: center.lat - 0.1,
            east: center.lng + 0.1,
            west: center.lng - 0.1
        }
        const options = {
            bounds: defaultBounds,
            types: ['address'],
            componentRestrictions: {country: "fr"},
            fields: ["address_components", "geometry", "name"],
            strictBounds: false,
        };
        const autocomplete = new google.maps.places.Autocomplete(input, options);

        let btn_search = document.getElementById("btn-search-address");

        btn_search.onclick = function () {
            let address = input.value;
            let geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address': address}, function (results, status) {
                if (status === 'OK') {
                    getAddressComponents(page, form);
                } else {
                    alert('Veuillez rentrer une adresse valide');
                }
            });
        }
        //Action when enter key is pressed
        input.addEventListener("keypress", (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                document.getElementById("btn-search-address").click();
            }

        });
        // Select first result when enter is pressed
        const selectFirstOnEnter = function (input) {  // store the original event binding function
            const _addEventListener = (input.addEventListener) ? input.addEventListener : input.attachEvent;

            function addEventListenerWrapper(type, listener) {  // Simulate a 'down arrow' keypress on hitting 'return' when no pac suggestion is selected, and then trigger the original listener.
                if (type === "keydown") {
                    const orig_listener = listener;
                    listener = function (event) {
                        const suggestion_selected = $(".pac-item-selected").length > 0;
                        if (event.which === 13 && !suggestion_selected) {
                            const simulated_downarrow = $.Event("keydown", {keyCode: 40, which: 40});
                            orig_listener.apply(input, [simulated_downarrow]);
                        }
                        orig_listener.apply(input, [event]);
                    };
                }
                _addEventListener.apply(input, [type, listener]); // add the modified listener
            }

            if (input.addEventListener) {
                input.addEventListener = addEventListenerWrapper;
            } else if (input.attachEvent) {
                input.attachEvent = addEventListenerWrapper;
            }
        };
        selectFirstOnEnter(input);
        function getAddressComponents(pageValue, formValue) {
            let addressArray;
            let address = "";
            const place = autocomplete.getPlace();
            let address1 = "";
            let postcode = "";
            let locality = "";
            let street_number = "";
            let route = "";
            let neighborhood = "";
            for (const component of place.address_components) {
                // @ts-ignore remove once typings fixed
                const componentType = component.types[0];
                switch (componentType) {
                    case "street_number": {
                        street_number = component.long_name;
                        address1 = `${component.long_name} ${address1}`;
                        break;
                    }
                    case "neighborhood": {
                        neighborhood = component.long_name;
                        address1 += component.short_name;
                        break;
                    }
                    case "route": {
                        route = component.long_name;
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
                    case "locality":
                        locality = component.long_name;
                        break;
                }
            }
            if (place.formatted_address) {
                address = place.formatted_address;
            } else {
                address = address1 + ", " + postcode + " " + locality;
            }
            let lat = place.geometry.location.lat();
            let lon = place.geometry.location.lng();
            let cp = postcode;
            let city = locality;
            let street = route;
            let houseNumber = street_number;
            addressArray = {
                latitude: lat,
                longitude: lon,
                addr: address,
                cp: cp,
                city: city,
                street: street,
                houseNumber: houseNumber,
                neighborhood: neighborhood
            }
            console.log(addressArray);
            console.log(formValue);
            console.log(pageValue);
            if (pageValue == "form" && formValue != null) {
                document.getElementById(formValue + "_form_address_coordinates").value = addressArray.latitude + " " + addressArray.longitude;
                // document.getElementById("register_longitude").value = addressArray.longitude;
                document.getElementById(formValue + "_form_address_postal_code").value = addressArray.cp;
                document.getElementById(formValue + "_form_address_city").value = addressArray.city;
                document.getElementById(formValue + "_form_address_street").value = addressArray.street;
                document.getElementById(formValue + "_form_address_house_number").value = addressArray.houseNumber;
                // document.getElementById("register_neighborhood").value = addressArray.neighborhood;

            }
            // sendToListingAddrController(addressArray);
        }
    }
}
