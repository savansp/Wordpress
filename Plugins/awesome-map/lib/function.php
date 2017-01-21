<?php

function awsmap_script() {
    if (isset($_GET['page']) && ($_GET['page'] == 'awesome-map/admin/init.php' || $_GET['page'] == 'awesome-map/admin/init.php/admin_appearance.php' || $_GET['page'] == 'awesome-map/admin/init.php/admin_setting.php')) {

        wp_register_style('awsmap_script1', plugins_url('../dist/css/style.css', __FILE__));
        wp_enqueue_style('awsmap_script1');

        wp_register_style('awsmap_script', plugins_url('../dist/css/bootstrap.css', __FILE__));
        wp_enqueue_style('awsmap_script');

        wp_register_style('awsmap_script2', plugins_url('../dist/css/bootstrap-colorpicker.css', __FILE__));
        wp_enqueue_style('awsmap_script2');

        wp_register_script('awsmap_script3', plugins_url('../dist/js/bootstrap.min.js', __FILE__));
        wp_enqueue_script('awsmap_script3');

        wp_register_script('awsmap_script4', plugins_url('../dist/js/bootstrap-colorpicker.min.js', __FILE__));
        wp_enqueue_script('awsmap_script4');
    }
}

add_action('admin_init', 'awsmap_script');

function aws_shortcode() {

    $hue = get_option('_awsmap_hue', '0');
    $grayscale = get_option('_awsmap_grayscale', '0');
    $light = get_option('_awsmap_light', '0');
    $zoom = get_option('_awsmap_zoom', '0');

    $max_width = get_option('_awsmap_max_width');
    $max_height = get_option('_awsmap_max_height');

    wp_register_script('googlemaps', '//maps.googleapis.com/maps/api/js?sensor=false', false, '3');
    wp_enqueue_script('googlemaps');
    ?>
    <style>
        .scrolloff {
            pointer-events: none;
        }
        #awsmap,.map-wrper{
            width:100%;
            max-width: <?php echo $max_width; ?>;
            height:<?php echo $max_height; ?>;
        }
    </style>
    <div class="map-wrper" >
        <div id="awsmap" >

        </div>
    </div>

    <script type="text/javascript">

        
        jQuery(document).ready(function () {
    <?php
    if (get_option('_awsmap_overlay') == 'true') {
        ?>
                jQuery('#awsmap').addClass('scrolloff');
                jQuery('.map-wrper').on('click', function () {
                   jQuery('#awsmap').removeClass('scrolloff');
                });

                jQuery(".map-wrper").mouseleave(function () {
                    jQuery('#awsmap').addClass('scrolloff');
                });
        <?php
    }
    ?>



            function  init() {
                var locations = [
    <?php
    $locs = get_option('_awsmap_locations');


    foreach ($locs as $loc) {
        ?>
                        ['<?php echo $loc[0] ?>', '<?php echo $loc[1] ?>', '<?php echo $loc[2] ?>', '<?php echo get_option('_awsmap_marker'); ?>','<?php echo $loc[3] ?>'],
        <?php
    }
    ?>
                ];




                var stylez = [
                    {
                        featureType: "all",
                        elementType: "all",
                        stylers: [
                            {hue: '<?php echo $hue ?>'},
                            {saturation: <?php echo $grayscale ?>},
                            {lightness: <?php echo $light ?>},
                        ]
                    }
                ];

                var map = new google.maps.Map(document.getElementById('awsmap'), {
                    zoom: 10,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    mapTypeControlOptions: {
                        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'tehgrayz']
                    },
                    streetViewControl: <?php echo get_option('_awsmap_street'); ?>,
                    panControl: true,
                    scrollwheel: <?php echo get_option('_awsmap_scroll'); ?>,
                    draggable: <?php echo get_option('_awsmap_dreggable'); ?>,
                    zoomControlOptions: {
                        position: google.maps.ControlPosition.LEFT_BOTTOM
                    }
                });

                var mapType = new google.maps.StyledMapType(stylez, {name: "Grayscale"});
                map.mapTypes.set('tehgrayz', mapType);
                map.setMapTypeId('tehgrayz');
    <?php
    if (get_option('_awsmap_infowindow') == 'true') {
        ?>
                    var infowindow = new google.maps.InfoWindow({
                        maxWidth: 360
                    });
    <?php } ?>
                var markers = new Array();



                // Add the markers and infowindows to the map
                for (var i = 0; i < locations.length; i++) {
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                        map: map,
                        icon: locations[i][3],
                    });

                    markers.push(marker);

                    google.maps.event.addListener(marker, 'click', (function (marker, i) {
                        return function () {
                        infowindow.setContent('<div class="infowindow"><div class="add-title">'+locations[i][0]+'</div><div class="add-content">'+locations[i][4]+'</div></div>');
                            infowindow.open(map, marker);

                        }
                    })(marker, i));

                    // We only have a limited number of possible icon colors, so we may have to restart the counter
                }

                function autoCenter() {
                    //  Create a new viewpoint bound
                    var bounds = new google.maps.LatLngBounds();
                    //  Go through each...
                    for (var i = 0; i < markers.length; i++) {
                        bounds.extend(markers[i].position);
                    }
                    //  Fit these bounds to the map
                    map.fitBounds(bounds);

    <?php if ($zoom != '0') { ?>
                        var listener = google.maps.event.addListener(map, "idle", function () {
                            map.setZoom(<?php echo $zoom; ?>);
                            google.maps.event.removeListener(listener);
                        });
    <?php } elseif (count($locs) == 1 && $zoom == 0) { ?>
                        var listener = google.maps.event.addListener(map, "idle", function () {
                            map.setZoom(10);
                            google.maps.event.removeListener(listener);
                        });
    <?php } ?>
                }
                autoCenter();

            }
            google.maps.event.addDomListener(window, 'load', init);

        });
    </script>

    <?php
}

add_shortcode('awesome-map', 'aws_shortcode');

function awsmap_app() {

    $hue = $_REQUEST['hue'];
    $grayscale = $_REQUEST['grayscale'];
    $light = $_REQUEST['light'];
    $zoom = $_REQUEST['zoom'];
    ?>

    <div id="awsmap">

    </div>

    <script type="text/javascript">
        
    <?php
    if (get_option('_awsmap_overlay') == 'true') {
        ?>
            jQuery('#awsmap').addClass('scrolloff');
            jQuery('.map-wrper').on('click', function () {
                jQuery('#awsmap').removeClass('scrolloff');
            });

            jQuery(".map-wrper").mouseleave(function () {
                jQuery('#awsmap').addClass('scrolloff');
            });
        <?php
    }
    ?>



        function  init() {
            var locations = [
    <?php
    $locs = get_option('_awsmap_locations');


    foreach ($locs as $loc) {
        ?>
                    ['<?php echo $loc[0] ?>', '<?php echo $loc[1] ?>', '<?php echo $loc[2] ?>', '<?php echo get_option('_awsmap_marker'); ?>','<?php echo $loc[3] ?>'],
        <?php
    }
    ?>
            ];




            var stylez = [
                {
                    featureType: "all",
                    elementType: "all",
                    stylers: [
                        {hue: '<?php echo $hue ?>'},
                        {saturation: <?php echo $grayscale ?>},
                        {lightness: <?php echo $light ?>},
                    ]
                }
            ];

            var map = new google.maps.Map(document.getElementById('awsmap'), {
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                mapTypeControlOptions: {
                    mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'tehgrayz']
                },
                streetViewControl: <?php echo get_option('_awsmap_street'); ?>,
                panControl: true,
                scrollwheel: <?php echo get_option('_awsmap_scroll'); ?>,
                draggable: <?php echo get_option('_awsmap_dreggable'); ?>,
                zoomControlOptions: {
                    position: google.maps.ControlPosition.LEFT_BOTTOM
                }
            });

            var mapType = new google.maps.StyledMapType(stylez, {name: "Grayscale"});
            map.mapTypes.set('tehgrayz', mapType);
            map.setMapTypeId('tehgrayz');
    <?php
    if (get_option('_awsmap_infowindow') == 'true') {
        ?>
                var infowindow = new google.maps.InfoWindow({
                    maxWidth: 360
                });
    <?php } ?>
            var markers = new Array();



            // Add the markers and infowindows to the map
            for (var i = 0; i < locations.length; i++) {
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map,
                    icon: locations[i][3],
                });

                markers.push(marker);

                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        infowindow.setContent('<div class="infowindow"><div class="add-title">'+locations[i][0]+'</div><div class="add-content">'+locations[i][4]+'</div></div>');
                        infowindow.open(map, marker);

                    }
                })(marker, i));

                // We only have a limited number of possible icon colors, so we may have to restart the counter
            }

            function autoCenter() {
                //  Create a new viewpoint bound
                var bounds = new google.maps.LatLngBounds();
                //  Go through each...
                for (var i = 0; i < markers.length; i++) {
                    bounds.extend(markers[i].position);
                }
                //  Fit these bounds to the map
                map.fitBounds(bounds);

    <?php if ($zoom != '0') { ?>
                    var listener = google.maps.event.addListener(map, "idle", function () {
                        map.setZoom(<?php echo $zoom; ?>);
                        google.maps.event.removeListener(listener);
                    });
    <?php } elseif (count($locs) == 1 && $zoom == 0) { ?>
                    var listener = google.maps.event.addListener(map, "idle", function () {
                        map.setZoom(10);
                        google.maps.event.removeListener(listener);
                    });
    <?php } ?>
            }
            autoCenter();

        }
        google.maps.event.addDomListener(window, 'load', init);


        init();
    </script>

    <?php
    die();
}

add_action('wp_ajax_awsmap_app', 'awsmap_app');
