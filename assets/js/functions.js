function suggetion() {
    $('#sug_input').keyup(function(e) {
        var formData = {
            'title': $('input[name=title]').val() 
        };

        if (formData['title'].length >= 1) {
            $.ajax({
                type: 'POST',
                url: 'ajax.php',
                data: formData,
                dataType: 'json',
                encode: true
            })
            .done(function(data) {
                $('#result').html(data).fadeIn();
                $('#result li').click(function() {
                    $('#sug_input').val($(this).text());
                    $('#result').fadeOut(500);
                });
            });
        } else {
            $.ajax({
                type: 'POST',
                url: 'ajax.php',
                data: {'title': ''},
                dataType: 'json',
                encode: true
            })
            .done(function(data) {
                $('#result').html(data).fadeIn();
                $('#result li').click(function() {
                    $('#sug_input').val($(this).text());
                    $('#result').fadeOut(500);
                });
            });
        }

        e.preventDefault();
    });
}
$(document).ready(function() {
    suggetion();
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true
    });
});
