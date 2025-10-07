// resources/js/app.js

import "./../../vendor/power-components/livewire-powergrid/dist/powergrid";
import "../../vendor/masmerise/livewire-toaster/resources/js";


window.addEventListener('load', function() {
    if (sessionStorage.getItem('was-on-403')) {
        sessionStorage.removeItem('was-on-403');
        // User came back from 403 page
        setTimeout(() => {
            window.location.reload();
        }, 100);
    }
});