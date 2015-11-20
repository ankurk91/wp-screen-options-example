(function (window, jQuery) {
    jQuery(function ($) {

        var form = $('div#screen-options-wrap').find('form').first();
        var submit = form.find('input:button');

        submit.click(function () {

            $.ajax({
                url: window.ajaxurl,
                data: form.serialize(),
                type: 'POST',
                cache: false,
                success: function (data, status, xhr) {
                    if (data == '1' && status == 'success') {
                        console.info('Settings saved');
                    }
                }, error: function (xhr, status, error) {
                    var err = 'Error Saving Options';
                    if (xhr.status === 0) {
                        err = "Could not connect to server ! Try Again..";
                    } else {
                        if (status === "timeout") {
                            err = "Connection Timeout ! Try Again..";
                        }
                        else {
                            err = "Unknown error.";
                        }
                    }
                    console.error(err);
                }
            });
        });
    });

})(window, jQuery);