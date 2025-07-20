// Bootstrap.js - Configuration de base pour StudiosDB v5 Pro

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Configuration CSRF pour Laravel
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Configuration pour Inertia.js
window.axios.defaults.headers.common['Accept'] = 'application/json';
