

document.addEventListener("DOMContentLoaded", function () {
    const input = document.querySelector("#customer_phone");
    window.intlTelInput(input, {
        initialCountry: "auto",
        fixDropdownWidth: false,
        containerClass: "phone-input-container",
        loadUtils: () => import("https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"),

        hiddenInput: (telInputName) => ({
            phone: "phone_full",       // will generate: <input type="hidden" name="phone_full">
            country: "country_code"
        }),
        geoIpLookup: (success, failure) => {
            fetch("https://ipapi.co/json")
                .then((res) => res.json())
                .then((data) => success(data.country_code))
                .catch(() => failure());
        },

    });



});

