$(document).ready(function(){
    $('#table').pageMe({
        pagerSelector: '#pagination',
        activeColor: 'blue',
        showPrevNext: true,
        hidePageNumbers: false,
        perPage: 1
    });
});