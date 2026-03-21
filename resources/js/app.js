import './bootstrap';

// Import Alpine.js and plugins
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

// Register plugins
Alpine.plugin(collapse);

// Make Alpine available globally
window.Alpine = Alpine;

// Start Alpine
Alpine.start();
