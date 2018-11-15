import 'flatpickr';
import 'flatpickr/dist/l10n/pl';
import 'flatpickr/dist/flatpickr.min.css';

$(document).ready(function () {
   $('.flatpickr').flatpickr({
       'locale': "pl",
       enableTime: true,
       time_24hr: true,
       // dateFormat: 'Y-m-d H:i'
   });
});