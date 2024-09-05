$('#btn_search').click(function(e) {
    e.defaultPrevented;
    $("#form_search").submit();
});
$('form#form_search input').keypress(function(e) {
    e.defaultPrevented;
    if(e.which == 13) {
        $("#form_search").submit();
        return false;
    }
});