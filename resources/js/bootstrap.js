import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';
// Livewire.start();
