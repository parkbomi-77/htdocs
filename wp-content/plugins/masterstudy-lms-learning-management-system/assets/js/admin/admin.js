"use strict";

(function ($) {
  $(document).ready(function () {
    $('body').on('click', '[data-field=wpcfto_addon_option_certificate_settings_title].wpcfto-box-child:not(.is_pro)', function () {
      $(this).closest('.wpcfto_group_started').toggleClass('open');
    });
    $('body').on('click', '.certificate_banner a.disabled', function (e) {
      e.preventDefault();
      var url = $(this).attr('href');
      var newUrl = document.querySelector('.data-url-certificate')['href'];
      ;
      $.ajax({
        url: url,
        success: function success() {
          window.location.href = newUrl;
        }
      });
    });
  });
})(jQuery);