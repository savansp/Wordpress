<?php

function awsmap_admin_appearance() {

    if (isset($_POST['awssbt'])) {

        $hue = $_POST['_awsmap_hue'];
        $grayscale = $_POST['_awsmap_grayscale'];
        $light = $_POST['_awsmap_light'];
        $zoom = $_POST['_awsmap_zoom'];

        update_option('_awsmap_hue', $hue);
        update_option('_awsmap_grayscale', $grayscale);
        update_option('_awsmap_light', $light);
        update_option('_awsmap_zoom', $zoom);
    }
    ?>
  <div class="stiky-code">
        <span class="lbl">Shortcode : </span>
        <input type="text" class="shrt-cd" value="[awesome-map]" readonly>
        <?php
        $locs = get_option('_awsmap_locations');

        if (count($locs) < 1 || !get_option('_awsmap_locations')) {
            ?>
            <div class="alrt">
                <span>Notice : </span>Add minimum One <a  href="<?php echo $url = admin_url('admin.php?page=awesome-map/admin/init.php'); ?>">Location</a>
            </div>
            <?php
            
        }
        ?>
    </div>
    <div class="col-md-12">
        <div class="aws-map">
            <h2> Appearance  </h2>
            <div class="row">
                <ul  class="nav nav-pills">
                    <li>
                        <a  href="<?php echo $url = admin_url('admin.php?page=awesome-map/admin/init.php'); ?>">Locations</a>
                    </li>
                    <li class="active">
                        <a href="<?php echo $url = admin_url('admin.php?page=awesome-map/admin/init.php/admin_appearance.php'); ?>"> Appearance </a>
                    </li>
                    <li>
                        <a href="<?php echo $url = admin_url('admin.php?page=awesome-map/admin/init.php/admin_setting.php'); ?>">Settings</a>
                    </li>

                </ul>
            </div>

            <form method="post" action="">
                <?php settings_fields('awsmap-settings-group'); ?>
                <?php do_settings_sections('awsmap-settings-group'); ?>


                <div class="row">
                    <div class="col-md-6">

                        <div class="helper styler">
                            <div class="col-md-12">
                                <div class="col-xs-4">
                                    <label>Color :</label>
                                </div>
                                <div class="col-xs-8">

                                    <input type="text"  id="hue" name="_awsmap_hue"  value="<?php echo get_option('_awsmap_hue', '') ?>" class="form-control" placeholder="Select Color"/>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-xs-4">
                                    <label>Grayscale : </label>
                                </div>
                                <div class="col-xs-8">
                                    <span class="full-bg">Full</span> <input type="range"  id="grayscale" name="_awsmap_grayscale" value="<?php echo get_option('_awsmap_grayscale', '0') ?>" class="form-control" min="-100" max="100" step="10">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-xs-4">
                                    <label>Brightness : </label>
                                </div>
                                <div class="col-xs-8">
                                    <input type="range" id="light" name="_awsmap_light" value="<?php echo get_option('_awsmap_light', '0') ?>" class="form-control" min="-100" max="100" step="10">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-xs-4">
                                    <label>Custom&nbsp;Zoom&nbsp;: </label>
                                </div>
                                <div class="col-xs-8">
<input type="checkbox" id="cstzm"  class="form-control" <?php if (get_option('_awsmap_zoom') && get_option('_awsmap_zoom') != '0') {
                echo'checked';
            } ?>>
                                     <span class="tiny-rmd" >Not Recommended For Multiple Locations </span>
                                        <div id="zoomreng"  <?php if (get_option('_awsmap_zoom') && get_option('_awsmap_zoom') != '0') {
                echo "style='display:block'";
            } else {
                echo "style='display:none'";
            } ?>>
                                            <input type="range" id="zoom" name="_awsmap_zoom" value="<?php if (get_option('_awsmap_zoom')) {
                echo get_option('_awsmap_zoom', '0');
            } else {
                echo '0';
            } ?>" class="form-control" min="0" max="15" step="1">
                                        </div>
                                </div>
                            </div>
                            <div class="col-md-12 cstmstyle">

                                <input type="button" id="reset_gray" value="Classic" class="btn btn-info">
                                <input type="button" id="reset_color" value="Gloriya" class="btn btn-info">
                                <input type="button" id="reset_normal" value="Reset To Normal" class="btn btn-default">
                            </div>

                            <div class="col-md-12">
                                <input type="submit" name="awssbt" value="Save Settings" class="btn btn-primary">
                            </div>

                            <script>
                                $ = jQuery.noConflict();
                                $(function () {

                                    $('#hue').colorpicker({
                                        customClass: 'colorpicker-2x',
                                        sliders: {
                                            saturation: {
                                                maxLeft: 200,
                                                maxTop: 200
                                            },
                                            hue: {
                                                maxTop: 200
                                            },
                                            alpha: {
                                                maxTop: 200
                                            }
                                        },
                                        colorSelectors: {
                                            '#777777': '#777777',
                                            '#337ab7': '#337ab7',
                                            '#5cb85c': '#5cb85c',
                                            '#5bc0de': '#5bc0de',
                                            '#f0ad4e': '#f0ad4e',
                                            '#d9534f': '#d9534f'
                                        }

                                    });
                                });
                            </script>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="helper">
                            <span class="spinner"></span>
                            
                            <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
                            <div class="map-wrper">

                            </div>


                        </div>
                    </div>

                </div>



            </form>
        </div>
    </div>

    <script>
        <?php         if (count($locs) > 0 && get_option('_awsmap_locations')) {
?>
                                ajaxmap = function () {

                                    var $hue = $('#hue').val();
                                    var $grayscale = $('#grayscale').val();
                                    var $light = $('#light').val();
                                    var $zoom = $('#zoom').val();


                                    $.ajax({
                                        url: "<?php echo bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php",
                                        type: 'POST',
                                        data: 'action=awsmap_app&hue=' + $hue + '&grayscale=' + $grayscale + '&light=' + $light + '&zoom=' + $zoom,
                                        beforeSend: function () {
                                            $('.spinner').addClass('is-active');
                                        },
                                        success: function (results)
                                        {
                                            $(".map-wrper").html(results);
                                            $('.spinner').removeClass('is-active');
                                        }
                                    });
                                };
                                $('#hue').on("blur", ajaxmap);
                                $('#grayscale,#light,#zoom').on("change", ajaxmap);


                                $('#cstzm').on("change", function () {
                                    $('#zoomreng').toggle();
                                    if (!$(this).is(':checked')) {
                                        $('#zoom').val('0');
                                        ajaxmap();
                                    }
                                });

                                jQuery(document).ready(function () {
                                    ajaxmap();
                                });
                                
                                
                                $('#reset_gray').on("click", function () {
                                    
                                        $('#hue').val('');
                                        $('#grayscale').val('-100');
                                        $('#light').val('0');
                                        
                                        ajaxmap();
                                    
                                });
                                $('#reset_color').on("click", function () {
                                    
                                        $('#hue').val('#028a8a');
                                        $('#grayscale').val('-40');
                                        $('#light').val('20');
                                                                               
                                        ajaxmap();
                                    
                                });
                                $('#reset_normal').on("click", function () {
                                    
                                        $('#hue').val('');
                                        $('#grayscale').val('0');
                                        $('#light').val('0');
                                        $('#zoom').val('0');
                                        
                                        ajaxmap();
                                    
                                });
        <?php } ?>
                                jQuery("input.shrt-cd").live('mouseup', function () {
                            jQuery(this).select();
                        });
                        jQuery(".stiky-code .lbl").live('mouseup', function () {
                            jQuery("input.shrt-cd").select();
                        });
    </script>
    <?php
}
