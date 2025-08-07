import './bootstrap';
import Alpine from 'alpinejs';

// Import components
import { searchBox } from './components/search-box.js';
import { categoryFilter } from './components/category-filter.js';
import { list } from './components/list.js';

// Import store
import { postsStore } from './stores/posts.js';

// Register Alpine.js store - call the function to get the store object
Alpine.store('posts', postsStore());

// Register Alpine.js components
Alpine.data('searchBox', searchBox);
Alpine.data('categoryFilter', categoryFilter);
Alpine.data('list', list);

// Start Alpine.js
window.Alpine = Alpine;
Alpine.start();
