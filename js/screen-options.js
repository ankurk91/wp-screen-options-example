(function (window, jQuery) {
  'use strict';

  jQuery(function ($) {

    var $form = $('div#screen-options-wrap').find('form').first(),
        $submit = $form.find('input:submit');

    $form.on('submit', function (e) {
      e.preventDefault();
      e.stopPropagation();

      $.ajax({
        url: window.ajaxurl, //get ajax url from html
        data: $form.serialize(),
        type: 'GET',
        cache: false,
        beforeSend: function () {
          // Show loader
          console.log('Sending..');
          $submit.prop('disabled', true);
        },
        complete: function () {
          // Hide loader
          console.log('Got response');
          $submit.prop('disabled', false);
        },
        success: function (data, status, xhr) {
          if (data.success === true && status === 'success') {
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