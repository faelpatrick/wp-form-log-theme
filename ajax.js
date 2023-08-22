jQuery(document).ready(function($) {
    $("#customForm").submit(function(event) {
        event.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            type: "POST",
            url: frontendajax.ajaxurl,
            data: formData,
            success: function(response) {
                alert(response);
                window.location = "/";
            },
            error: function() {
                alert("Error.");
            }
        });
    });
});
