<?php

$s['header'] .= '<script src="http://maps.google.com/maps/api/js?sensor=false"></script>';

class Router {
    private $id;
    private $address;
    private $zoomlevel;
    private $latitude;
    private $longitude;
    private $routeDescriptor;

    function __construct($address, $zoomlevel, $latitude, $longitude, $id = 'router') {
        $this->id = $id;
        $this->address = $address;
        $this->zoomlevel = $zoomlevel;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->routeDescriptor = 'Fill in your address to get a route description';
    }

    function setRouteDescriptor($routeDescriptor) {
        $this->routeDescriptor = $routeDescriptor;
    }

    function getId() {
        return $this->id;
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
