<div id="wrap">
<div id="map"></div>
<div class="directions_title"><?= $s['d']->getRouteDescriptor(); ?></div>
<form action="" onsubmit="calcRoute(); return false">
    <input type="text" id="route_start">
    <input type="submit" id="route_submit">
</form>
<div id="directions_panel"></div>
    <noscript><b>JavaScript must be enabled in order for you to use Google Maps.</b> 
      However, it seems JavaScript is either disabled or not supported by your browser. 
      To view Google Maps, enable JavaScript by changing your browser options, and then 
      try again.
    </noscript>

    <script type="text/javascript">
        function calcRoute() {
            var start = document.getElementById("route_start").value;
            var end = "<?= $s['d']->getAddress(); ?>";
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
        var latlng = new google.maps.LatLng(<?= $s['d']->getPosition(); ?>);
        var directionsDisplay = new google.maps.DirectionsRenderer();
        var myOptions = {
            zoom: <?= $s['d']->getZoomLevel(); ?>,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapTypeControl: false
        };
        var map = new google.maps.Map(document.getElementById("map"),myOptions);
        directionsDisplay.setMap(map);
        directionsDisplay.setPanel(document.getElementById("directions_panel"));
        var marker = new google.maps.Marker({
            position: latlng, 
            map: map,
            title:"<?= $s['d']->getAddress(); ?>"
        });
        google.maps.event.addListener(marker, 'click', function() {
            map.setZoom(<?= $s['d']->getZoomLevel(); ?>);
            map.setCenter(marker.getPosition());
        });
    </script>
</div>
