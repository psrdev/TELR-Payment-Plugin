async function fetchCountries() {

    try {
        const response = await fetch("https://restcountries.com/v3.1/all?name,cca2");
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        const countries = await response.json();
        return countries;
    } catch (error) {
        console.error("Error fetching countries:", error);
        return [];
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const input = document.querySelector("#customer_phone");
    window.intlTelInput(input, {
        initialCountry: "auto",
        fixDropdownWidth: false,
        containerClass: "phone-input-container",
        geoIpLookup: (success, failure) => {
            fetch("https://ipapi.co/json")
                .then((res) => res.json())
                .then((data) => success(data.country_code))
                .catch(() => failure());
        },

    });


});

document.addEventListener("DOMContentLoaded", function () {
    (async function () {
        const nationalitySelect = document.querySelector("#customer_nationality");
        const countryOfResidenceSelect = document.querySelector("#customer_country_of_residence");
        const excludeCountries = ["US", "CA", "GB", "AU", "NZ", "IE", "ZA"]; // List of countries to exclude
        const countries = await fetchCountries();
        countries.forEach(country => {
            // Skip excluded countries
            if (excludeCountries.includes(country.cca2)) {
                return;
            }
            const option = document.createElement("option");
            option.value = country.cca2.toLowerCase();
            option.textContent = country.name.common;
            nationalitySelect.appendChild(option);
            countryOfResidenceSelect.appendChild(option.cloneNode(true));
        });
    })();
});