// resources/js/app.js

import "./../../vendor/power-components/livewire-powergrid/dist/powergrid";
import "../../vendor/masmerise/livewire-toaster/resources/js";

// window.addEventListener('load', function() {
//     if (sessionStorage.getItem('was-on-403')) {
//         sessionStorage.removeItem('was-on-403');
//         // User came back from 403 page
//         setTimeout(() => {
//             window.location.reload();
//         }, 100);
//     }
// });

window.addEventListener('load', function() {
    const wasOn403 = sessionStorage.getItem('was-on-403');
    const is403Page = window.location.pathname.includes('/403') || 
                      document.title.includes('403') ||
                      document.querySelector('body').textContent.includes('Access Forbidden');
    
    // Only reload if we're NOT on the 403 page
    if (wasOn403 === 'true' && !is403Page) {
        sessionStorage.removeItem('was-on-403');
        window.location.reload();
    }
});