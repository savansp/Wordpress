<?php

function awsmap_admin_setting() {

    wp_enqueue_media();

    if (isset($_POST['awssbt'])) {

        $marker = $_POST['_awsmap_marker'];
        update_option('_awsmap_marker', $marker);

        if (isset($_POST['_awsmap_street']) && $_POST['_awsmap_street'] == 'true') {
            update_option('_awsmap_street', 'true');
        } else {
            update_option('_awsmap_street', 'false');
        }
        if (isset($_POST['_awsmap_overlay']) && $_POST['_awsmap_overlay'] == 'true') {
            update_option('_awsmap_overlay', 'true');
        } else {
            update_option('_awsmap_overlay', 'false');
        }
        if (isset($_POST['_awsmap_scroll']) && $_POST['_awsmap_scroll'] == 'true') {
            update_option('_awsmap_scroll', 'true');
        } else {
            update_option('_awsmap_scroll', 'false');
        }
        if (isset($_POST['_awsmap_dreggable']) && $_POST['_awsmap_dreggable'] == 'true') {
            update_option('_awsmap_dreggable', 'true');
        } else {
            update_option('_awsmap_dreggable', 'false');
        }
        if (isset($_POST['_awsmap_infowindow']) && $_POST['_awsmap_infowindow'] == 'true') {
            update_option('_awsmap_infowindow', 'true');
        } else {
            update_option('_awsmap_infowindow', 'false');
        }

        if (isset($_POST['_awsmap_max_width']) && $_POST['_awsmap_max_width'] != '') {
            update_option('_awsmap_max_width', $_POST['_awsmap_max_width']);
        }
        if (isset($_POST['_awsmap_max_height']) && $_POST['_awsmap_max_height'] != '') {
            update_option('_awsmap_max_height', $_POST['_awsmap_max_height']);
        }
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
            <h2> Settings  </h2>
            <div class="row">
                <ul  class="nav nav-pills">
                    <li >
                        <a  href="<?php echo $url = admin_url('admin.php?page=awesome-map/admin/init.php'); ?>">Locations</a>
                    </li>
                    <li>
                        <a href="<?php echo $url = admin_url('admin.php?page=awesome-map/admin/init.php/admin_appearance.php'); ?>"> Appearance </a>
                    </li>
                    <li class="active">
                        <a href="<?php echo $url = admin_url('admin.php?page=awesome-map/admin/init.php/admin_setting.php'); ?>">Settings</a>
                    </li>

                </ul>
            </div>

            <form method="post" action="">
                <?php settings_fields('awsmap-settings-group'); ?>
                <?php do_settings_sections('awsmap-settings-group'); ?>


                <div class="row">
                    <div class="col-md-8 helper setting-wrper">
                        <div class="col-md-12">
                            <div class="col-xs-4">
                                <label for="image_url">Marker : </label>
                            </div>
                            <div class="col-xs-8">
                                <div class="col-xs-4">
                                    <input type="hidden" name="_awsmap_marker" id="image_url" class="regular-text" value="<?php echo get_option('_awsmap_marker'); ?>">
                                    <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload Image">
                                </div>
                                <div class="prev-marker col-xs-4">
                                    <img src="<?php echo get_option('_awsmap_marker'); ?>">

                                </div>
                                <div class="col-xs-4">
                                    <span class="button" id="remove_marker"><i class="glyphicon glyphicon-repeat"></i> Restore</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-xs-4">
                                <label >Street View Control : </label>
                            </div>
                            <div class="col-xs-8">
                                <input type="checkbox" name="_awsmap_street"  value="true" <?php if (get_option('_awsmap_street') == 'true') echo "checked"; ?>>
                            </div>
                        </div>
                        <div class="col-md-12 featured">
                            <span class="label label-danger buges">New</span>
                            <div class="col-xs-4">

                                <label >Mobile & scroll Compatible : </label>
                            </div>
                            <div class="col-xs-8">
                                <input type="checkbox" name="_awsmap_overlay"  id="overlay" value="true" <?php if (get_option('_awsmap_overlay') == 'true') echo "checked"; ?>><label for="overlay" class="inf">Scroll and Drag Functionality running after one click </label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-xs-4">
                                <label > Scroll : </label>
                            </div>
                            <div class="col-xs-8">
                                <input type="checkbox" name="_awsmap_scroll" id="scrolling" value="true" <?php if (get_option('_awsmap_scroll') == 'true') echo "checked"; ?> <?php if (!get_option('_awsmap_scroll')) echo "checked"; ?>>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-xs-4">
                                <label > Draggable : </label>
                            </div>
                            <div class="col-xs-8">
                                <input type="checkbox" name="_awsmap_dreggable" id="dreggable" value="true" <?php if (get_option('_awsmap_dreggable') == 'true') echo "checked"; ?> <?php if (!get_option('_awsmap_dreggable')) echo "checked"; ?>>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-xs-4">
                                <label > Info Window : </label>
                            </div>
                            <div class="col-xs-8 infowindow">
                                <input type="checkbox" id="infowindow" name="_awsmap_infowindow"  value="true" <?php if (get_option('_awsmap_infowindow') == 'true') echo "checked"; ?> <?php if (!get_option('_awsmap_infowindow')) echo "checked"; ?>><label for="infowindow" class="inf">Display Title in info window on marker click </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-xs-4">
                                <label > Maximum Width : </label>
                            </div>
                            <div class="col-xs-8">
                                <input type="text" name="_awsmap_max_width"  value="<?php
                                if (!get_option('_awsmap_max_width')) {
                                    echo '1000px';
                                } else {
                                    echo get_option('_awsmap_max_width');
                                }
                                ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-xs-4">
                                <label > Maximum Height : </label>
                            </div>
                            <div class="col-xs-8">
                                <input type="text" name="_awsmap_max_height" value="<?php
                                if (!get_option('_awsmap_max_height')) {
                                    echo '400px';
                                } else {
                                    echo get_option('_awsmap_max_height');
                                }
                                ?>" >
                            </div>
                        </div>

                        <div class="col-md-12">
                            <input type="submit" name="awssbt" value="submit" class="btn btn-primary">
                        </div>

                    </div>




                    <script>
                        jQuery(document).ready(function ($) {
                            $('#upload-btn').click(function (e) {
                                e.preventDefault();
                                var image = wp.media({
                                    title: 'Upload Image',
                                    // mutiple: true if you want to upload multiple files at once
                                    multiple: false
                                }).open()
                                        .on('select', function (e) {
                                            // This will return the selected image from the Media Uploader, the result is an object
                                            var uploaded_image = image.state().get('selection').first();
                                            // We convert uploaded_image to a JSON object to make accessing it easier
                                            // Output to the console uploaded_image
                                            console.log(uploaded_image);
                                            var image_url = uploaded_image.toJSON().url;
                                            // Let's assign the url value to the input field
                                            $('#image_url').val(image_url);
                                            $('.prev-marker img').attr('src', image_url)
                                        });
                            });

                            jQuery('#remove_marker').on('click', function () {
                                jQuery('#image_url').val('<?php echo plugin_dir_url(dirname(__FILE__)) . 'dist/img/marker-default.jpg'; ?>');
                                jQuery('.prev-marker img').attr('src', '<?php echo plugin_dir_url(dirname(__FILE__)) . 'dist/img/marker-default.jpg'; ?>')
                            });

                            jQuery('#overlay').on("change", function () {

                                if (jQuery(this).is(':checked')) {
                                    jQuery('#scrolling').prop("checked", true);
                                    jQuery('#dreggable').prop("checked", true);
                                }
                            });

                        });
                        jQuery("input.shrt-cd").live('mouseup', function () {
                            jQuery(this).select();
                        });
                        jQuery(".stiky-code .lbl").live('mouseup', function () {
                            jQuery("input.shrt-cd").select();
                        });
                    </script>




            </form>
        </div>
    </div>

    <?php
}
