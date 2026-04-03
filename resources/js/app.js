import './bootstrap';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Initialize tooltips and popovers globally
document.addEventListener('DOMContentLoaded', () => {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
});

// FullCalendar Integration
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import bootstrap5Plugin from '@fullcalendar/bootstrap5';

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        var calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, interactionPlugin, bootstrap5Plugin],
            themeSystem: 'bootstrap5',
            initialView: 'dayGridMonth',
            events: '/reservations/calendar-events',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek,dayGridDay'
            },
            eventClick: function(info) {
                if (info.event.url) {
                    window.location.href = info.event.url;
                    info.jsEvent.preventDefault();
                }
            }
        });
        calendar.render();
    }
});

import './echo';

// Global Pusher Listener for Admin Notifications
document.addEventListener('DOMContentLoaded', () => {
    if(window.Echo) {
        window.Echo.channel('hotel-reservations')
        .listen('.reservation.created', (e) => {
            console.log('Real-Time Booking Triggered:', e);
            // Create dynamic Bootstrap Toast for Glassmorphism UI
            const toastHtml = `
            <div class="toast align-items-center text-white bg-success border-0 glass-panel mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong><i class="bi bi-bell-fill me-2"></i> ${e.message}</strong><br>
                        <span class="small opacity-75">Revenue Impact: $${e.total_price}</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>`;
            
            let container = document.getElementById('toast-container');
            if(!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
                document.body.appendChild(container);
            }

            container.insertAdjacentHTML('beforeend', toastHtml);
            const newToast = container.lastElementChild;
            const bsToast = new bootstrap.Toast(newToast, {delay: 10000});
            bsToast.show();
        });
    }
});
