$(function () {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });    
});
$('[data-submenu]').submenupicker();
$('.fecha').datepicker({
    format: 'yyyy-mm-dd',
    language: 'es',
    autoclose: true,
    clearBtn: true,
    todayHighlight: true
});
