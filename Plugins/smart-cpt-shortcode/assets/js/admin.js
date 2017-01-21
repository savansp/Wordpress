(function ($) {

    $(document).ready(function () {
        
        $("body").on("click", ".shrt-cd input ,.main_shortcode", function () {
            $(this).select();
        });

        $("#ptype").change(function () {
            var Data = "ptype=" + $(this).val() + "&pid=" + $('#cpl_post_id').val() + "&action=cpl_tax_dropdown";
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: Data,
                success: function (response) {
                    $('#cpl_tex').html(response);
                    $('#cpl_tex #loader_aj').remove();
                    $(".select2").select2({width: 'calc(100% - 110px)'});
                },
                beforeSend: function () {
                    $('#cpl_tex').append('<div id="loader_aj"><span class="spinner is-active"></span></div>');
                },
                error: function () {
                    console.log('failed');
                }
            });
        });

        $("#ptype").change(function () {
            var Data = "ptype=" + $(this).val() + "&action=cpl_acf_keys";
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: Data,
                success: function (response) {
                    $('#acf_shortcode').html(response);
                    $('#acf_shortcode #loader_aj').remove();
                },
                beforeSend: function () {
                    $('#acf_shortcode').append('<div id="loader_aj"><span class="spinner is-active"></span></div>');
                },
                error: function () {
                    console.log('failed');
                }
            });
        });
        
        if($("#ptype").val() !== '0'){
             var Data = "ptype=" + $("#ptype").val() + "&action=cpl_acf_keys";
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: Data,
                success: function (response) {
                    $('#acf_shortcode').html(response);
                    $('#acf_shortcode #loader_aj').remove();
                },
                beforeSend: function () {
                    $('#acf_shortcode').append('<div id="loader_aj"><span class="spinner is-active"></span></div>');
                },
                error: function () {
                    console.log('failed');
                }
            });
            
        }
        $("#ptype").change(function () {
            var Data = "ptype=" + $(this).val() + "&action=cpl_tax_shortcode";
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: Data,
                success: function (response) {
                    $('#cpl_tax_shortcode').html(response);
                    
                },
                
                error: function () {
                    console.log('failed');
                }
            });
        });
        if($("#ptype").val() !== '0'){
             var Data = "ptype=" + $("#ptype").val() + "&action=cpl_tax_shortcode";
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: Data,
                success: function (response) {
                    $('#cpl_tax_shortcode').html(response);
                    
                },
                
                error: function () {
                    console.log('failed');
                }
            });
            
        }


        if ($('.select2').length != 0) {
            $(".select2").select2({width: 'calc(100% - 110px)'});
        }




        if ($('#view_contex').length != 0) {
            var editor = CodeMirror.fromTextArea(document.getElementById("view_contex"), {
                lineNumbers: true,
                mode: "xml"
            });
        }
        if ($('#cpl_style_inline').length != 0) {
            var editor = CodeMirror.fromTextArea(document.getElementById("cpl_style_inline"), {
                lineNumbers: true,
                matchBrackets: true,
                mode: "text/x-scss"
            });
        }

        if ($('#cpl_default_tpl').length != 0) {
            var editor = CodeMirror.fromTextArea(document.getElementById("cpl_default_tpl"), {
                lineNumbers: true,
                mode: "xml"
            });
             
        }
        if ($('#cpl_inline_style').length != 0) {
            var editor = CodeMirror.fromTextArea(document.getElementById("cpl_inline_style"), {
                lineNumbers: true,
               matchBrackets: true,
                mode: "text/x-scss"
            });
        }

    });

})(jQuery);
