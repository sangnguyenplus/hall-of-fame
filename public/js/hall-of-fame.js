(function ($) {
    'use strict';

    $(document).ready(function () {
        // File input preview
        $('.file-input').on('change', function () {
            const fileCount = this.files.length;
            if (fileCount > 0) {
                $(this).next('.form-text').text(fileCount + ' file(s) selected');
            } else {
                $(this).next('.form-text').text('No files selected');
            }
        });

        // Form validation
        $('#vulnerability-report-form').on('submit', function () {
            const form = $(this);
            let valid = true;

            form.find('[required]').each(function () {
                if (!$(this).val()) {
                    $(this).addClass('is-invalid');
                    valid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            return valid;
        });
    });
})(jQuery);
