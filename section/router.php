<?php

$router = $s['d'];
$prefix = $router->getId() . '_';

?>
<div id="<?= $prefix ?>map" class="map"></div>
<div class="directions_title"><?= $router->getRouteDescriptor(); ?></div>
<form action="" onsubmit="calcRoute(); return false">
    <input type="text" id="<?= $prefix ?>route_start">
    <input type="submit" id="<?= $prefix ?>route_submit">
</form>
<div id="<?= $prefix ?>directions_panel" class="directions_panel"></div>
<noscript>
    <b>JavaScript must be enabled in order for you to use Google Maps.</b> 
    However, it seems JavaScript is either disabled or not supported by your browser. 
    To view Google Maps, enable JavaScript by changing your browser options, and then 
    try again.
</noscript>
<script>
    function calcRoute() {
        var start = document.getElementById("<?= $prefix ?>route_start").value;
        var end = "<?= $router->getAddress(); ?>";
        var request = {
            origin:start,
            destination:end,
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        };
        directionsService.route(request, function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
            }
        });
    }

    var directionsService = new google.maps.DirectionsService();
    var latlng = new google.maps.LatLng(<?= $router->getPosition(); ?>);
    var directionsDisplay = new google.maps.DirectionsRenderer();
    var myOptions = {
        zoom: <?= $router->getZoomLevel(); ?>,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false
    };
    var map = new google.maps.Map(document.getElementById("<?= $prefix ?>map"),myOptions);
    directionsDisplay.setMap(map);
    directionsDisplay.setPanel(document.getElementById("<?= $prefix ?>directions_panel"));
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        title:"<?= $router->getAddress(); ?>"
    });
    google.maps.event.addListener(marker, 'click', function() {
        map.setZoom(<?= $router->getZoomLevel(); ?>);
        map.setCenter(marker.getPosition());
    });
</script>
