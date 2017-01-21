(function ($) {
    $(document).ready(function () {
        $("#post").submit(function (event) {

            var post_title = $('input[name="post_title"]').val();

            if (post_title == '') {
                alert('Post Type Slug Can`t Blank');
                event.preventDefault();
            }
        });
        $(".add").click(function () {
            var count = $('#here > .col-1').length;

            $('#here').append('<div class="col-1"><label> Taxonomy : </label><input type="text" name="cpl_post_type_taxonomy[' + count + ']" value="" /> <span class="remove dashicons dashicons-no"></span></div>');

            return false;
        });
        $(".remove").live('click', function () {
            if (confirm("Are you sure you want to delete this?")) {
                $(this).parent().remove();
            }
            else {
                return false;
            }

        });
    });
})(jQuery);
