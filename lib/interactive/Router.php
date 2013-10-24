<?php

$s['header'] .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';

class Router {
    private $address;
    private $zoomlevel;
    private $latitude;
    private $longitude;
    private $routeDescriptor;

    function __construct($address, $zoomlevel, $latitude, $longitude, $routeDescriptor = 'Fill in your address to get a route description') {
        $this->address = $address;
        $this->zoomlevel = $zoomlevel;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->routeDescriptor = $routeDescriptor;
    }

    function getAddress() {
        return $this->address;
    }

    function getZoomLevel() {
        return $this->zoomlevel;
    }

    function getPosition() {
        return $this->latitude . ', ' . $this->longitude;
    }

    function getRouteDescriptor() {
        return $this->routeDescriptor;
    }
}

?>
