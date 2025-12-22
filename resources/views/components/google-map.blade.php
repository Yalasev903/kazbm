@once
<style>
    gmpx-store-locator {
        display: block;
        width: 100%;
        height: 100%;
        min-height: 400px;
        background: #f8f9fa;

        /* User's suggested styles */
        --gmpx-color-surface: #fff;
        --gmpx-color-on-surface: #212121;
        --gmpx-color-on-surface-variant: #757575;
        --gmpx-color-primary: #1967d2;
        --gmpx-color-outline: #e0e0e0;
        --gmpx-fixed-panel-width-row-layout: 28.5em;
        --gmpx-fixed-panel-height-column-layout: 65%;
        --gmpx-font-family-base: "Roboto", sans-serif;
        --gmpx-font-family-headings: "Roboto", sans-serif;
        --gmpx-font-size-base: 0.875rem;
        --gmpx-hours-color-open: #188038;
        --gmpx-hours-color-closed: #d50000;
        --gmpx-rating-color: #ffb300;
        --gmpx-rating-color-empty: #e0e0e0;
    }
</style>

<script type="module">
    const GOOGLE_MAPS_CONFIG = {
        "locations": [
            {
                "title": "Облицовочный кирпич в Павлодаре - KAZBM",
                "address1": "ул.Академика Сатпаева, 11",
                "address2": "Павлодар, Казахстан",
                "coords": {"lat": 52.2955489, "lng": 76.940955},
                "placeId": "ChIJA2taUBi1-UIRLbMI3BFLHNM"
            }
        ],
        "mapOptions": {
            "center": {"lat": 52.2955489, "lng": 76.940955},
            "fullscreenControl": true,
            "mapTypeControl": false,
            "streetViewControl": false,
            "zoom": 15,
            "zoomControl": true,
            "maxZoom": 17,
            "mapId": ""
        },
        "mapsApiKey": "{{ config('services.google_maps.api_key') }}",
        "capabilities": {
            "input": false,
            "autocomplete": false,
            "directions": false,
            "distanceMatrix": false,
            "details": false,
            "actions": false
        }
    };

    const initAllMaps = async () => {
        try {
            await customElements.whenDefined('gmpx-store-locator');
            const locators = document.querySelectorAll('gmpx-store-locator:not([data-configured])');
            if (locators.length === 0) return;

            locators.forEach(locator => {
                // Check if element is in DOM and has height (or we are just configuring it)
                locator.configureFromQuickBuilder(GOOGLE_MAPS_CONFIG);
                locator.setAttribute('data-configured', 'true');
            });
        } catch (e) {
            // Silently retry or log if needed
        }
    };

    // Immediate attempt
    initAllMaps();

    // Standard events
    window.addEventListener('load', initAllMaps);
    document.addEventListener('DOMContentLoaded', initAllMaps);
    
    // Robust retries for preloader cases
    setTimeout(initAllMaps, 500);
    setTimeout(initAllMaps, 1500);
    setTimeout(initAllMaps, 3000);

    // Observe DOM changes (like preloader removal)
    const observer = new MutationObserver((mutations) => {
        initAllMaps();
    });
    observer.observe(document.body, { childList: true, subtree: true });
</script>

<script type="module" src="https://ajax.googleapis.com/ajax/libs/@googlemaps/extended-component-library/0.6.11/index.min.js"></script>

@if(config('services.google_maps.api_key'))
    <gmpx-api-loader key="{{ config('services.google_maps.api_key') }}" solution-channel="GMP_QB_locatorplus_v11_c" libraries="places"></gmpx-api-loader>
@endif
@endonce

<gmpx-store-locator map-id="DEMO_MAP_ID"></gmpx-store-locator>






