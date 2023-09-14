jQuery(document).ready(function($){
    // On focus for input fields
    $(".wpcf7-form input").focus(function() {
        $(this).parent().siblings('label').addClass('has-value');
    }).blur(function() {
        var text_val = $(this).val();
        if(text_val === "") {
            $(this).parent().siblings('label').removeClass('has-value');
        }
    });

    // On focus for textarea fields
    $(".wpcf7-form textarea").focus(function() {
        $(this).parent().siblings('label').addClass('has-value');
    }).blur(function() {
        var text_val = $(this).val();
        if(text_val === "") {
            $(this).parent().siblings('label').removeClass('has-value');
        }
    });
});
