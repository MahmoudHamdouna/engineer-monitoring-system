import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

document.addEventListener('DOMContentLoaded', function() {
    const userId = document.querySelector('meta[name="user-id"]').content;

    window.Echo.private(`notifications.${userId}`)
        .listen('TaskAssigned', (e) => {
            console.log('Received:', e);

            const dropdown = document.querySelector('#notificationDropdown');
            const badge = document.querySelector('#notificationBadge');

            let count = parseInt(badge.textContent || 0);
            badge.textContent = count + 1;

            let li = document.createElement('li');
            li.innerHTML = `
                <a href="${e.link}" class="dropdown-item fw-bold">
                    ${e.title}
                </a>
            `;
            dropdown.prepend(li);
        });
});