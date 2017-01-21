<?php

function awsmap_admin_home() {

    if (isset($_POST['awssbt'])) {

        $title = array_filter($_POST['title']);
        $address = array_filter($_POST['address']);
        $lat = array_filter($_POST['lat']);
        $long = array_filter($_POST['long']);

        $lat_long = array();

        for ($i = 0; $i < count($title); $i++) {

            $lat_long[$i] = array();

            array_push($lat_long[$i], $title[$i], $lat[$i], $long[$i], $address[$i]);
        }


        update_option('_awsmap_locations', $lat_long);
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
            <h2> Locations  </h2>
            <div class="row">
            <ul  class="nav nav-pills">
                <li class="active">
                    <a  href="<?php echo $url = admin_url('admin.php?page=awesome-map/admin/init.php');?>">Locations</a>
                </li>
                <li>
                    <a href="<?php echo $url = admin_url('admin.php?page=awesome-map/admin/init.php/admin_appearance.php');?>"> Appearance </a>
                </li>
                <li>
                    <a href="<?php echo $url = admin_url('admin.php?page=awesome-map/admin/init.php/admin_setting.php');?>">Settings</a>
                </li>

            </ul>
            </div>

            <form method="post" action="">
                <?php settings_fields('awsmap-settings-group'); ?>
                <?php do_settings_sections('awsmap-settings-group'); ?>


                <div class="row">
                    <div class="col-md-8">
                        <div class="input_fields_wrap">

                            <?php
                            if (!get_option('_awsmap_locations')) {
                                ?>
                                <div class="row">
                                    <div class="col-xs-4"><input type="text" name="title[]"  class="form-control" placeholder="Title"></div>
                                    <div class="col-xs-4"><input type="text" name="lat[]"  class="form-control" placeholder="Latitude"></div>
                                    <div class="col-xs-4"><input type="text" name="long[]" class="form-control" placeholder="Longitude"></div>
                                    <div class="col-xs-10"><textarea name="address[]" class="form-control" placeholder="Address"></textarea></div>
                                    <div class="col-xs-2">
                                        <a class="add_field_button"><i class="glyphicon glyphicon-plus"></i> &nbsp;Add</a>
                                    </div>
                                </div>
                                <?php
                            } else {

                                $locs = get_option('_awsmap_locations');

                                foreach ($locs as $loc) {
                                    ?>
                                    <div class="row">
                                        <div class="col-xs-4"><input type="text" name="title[]"  class="form-control" value="<?php echo $loc[0]; ?>"></div>
                                        <div class="col-xs-4"><input type="text" name="lat[]"  class="form-control" value="<?php echo $loc[1]; ?>"></div>
                                        <div class="col-xs-4"><input type="text" name="long[]" class="form-control" value="<?php echo $loc[2]; ?>"></div>
                                        <div class="col-xs-10"><textarea name="address[]" class="form-control" ><?php echo $loc[3]; ?></textarea></div>
                                        <div class="col-xs-2">
                                            <a href="#" class="remove_field"><i class="glyphicon glyphicon-remove"></i></a>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="row">
                                    <div class="col-xs-4"><input type="text" name="title[]"  class="form-control" placeholder="Title"></div>
                                    <div class="col-xs-4"><input type="text" name="lat[]"  class="form-control" placeholder="Latitude"></div>
                                    <div class="col-xs-4"><input type="text" name="long[]" class="form-control" placeholder="Longitude"></div>
                                    <div class="col-xs-10"><textarea name="address[]" class="form-control" placeholder="Address"></textarea></div>
                                    <div class="col-xs-2">
                                        <a class="add_field_button"><i class="glyphicon glyphicon-plus"></i> &nbsp;Add</a>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                        </div>

                    </div>
                    <div class="col-md-4">

                        <div class="helper">

                            <div class="alert alert-success">
                                Click on map to get latitude and longitude
                            </div>
                            <label>Latitude:</label>
                            <input type="text" id="latfatch" class="form-control" readonly="true">
                            <label>Longitude:</label>
                            <input type="text" id="longfatch" class="form-control" readonly="true">
                            </br>
                            <div id="map" style="width:100%;height:300px;">

                            </div>

                        </div>

                        <script>$ = jQuery.noConflict();</script>
                        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

                        <script type="text/javascript">

                            function initialize() {
                                var mapProp = {
                                    center: new google.maps.LatLng(23.0225, 72.5714),
                                    zoom: 5,
                                    mapTypeId: google.maps.MapTypeId.ROADMAP
                                };
                                var map = new google.maps.Map(document.getElementById("map"), mapProp);
                                google.maps.event.addListener(map, 'click', function (event) {
                                    document.getElementById('latfatch').value = event.latLng.lat();
                                    document.getElementById('longfatch').value = event.latLng.lng();

                                });
                            }
                            google.maps.event.addDomListener(window, 'load', initialize);
                        </script>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <input type="submit" name="awssbt" value="submit" class="btn btn-primary">
                    </div>
                </div>


                <script>
                    $ = jQuery.noConflict();
                    $(document).ready(function () {
                        var max_fields = 5; //maximum input boxes allowed
                        var wrapper = $(".input_fields_wrap"); //Fields wrapper
                        var add_button = $(".add_field_button"); //Add button ID

                        var x = 1; //initlal text box count
                        $(add_button).click(function (e) { //on add input button click
                            e.preventDefault();
                            if (x < max_fields) { //max input box allowed
                                x++; //text box increment
                                $(wrapper).append('<div class="row"><div class="col-xs-4"><input type="text" name="title[]"  class="form-control" placeholder="Title"></div><div class="col-xs-4"><input type="text" name="lat[]"  class="form-control" placeholder="Latitude"></div><div class="col-xs-4"><input type="text" name="long[]" class="form-control" placeholder="Longitude"></div><div class="col-xs-10"><textarea name="address[]" class="form-control" placeholder="Address"></textarea></div><div class="col-xs-2"><a href="#" class="remove_field"><i class="glyphicon glyphicon-remove"></i></a></div></div>');
                            }
                        });

                        $(wrapper).on("click", ".remove_field", function (e) { //user click on remove text
                            e.preventDefault();
                            $(this).parent().parent().remove();
                            x--;
                        })
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
