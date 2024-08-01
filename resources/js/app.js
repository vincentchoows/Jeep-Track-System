import './bootstrap';

import Alpine from 'alpinejs';

import jQuery from 'jquery';
window.$ = jQuery;
  
import swal from 'sweetalert2';
window.Swal = swal;

window.Alpine = Alpine;

Alpine.start();
