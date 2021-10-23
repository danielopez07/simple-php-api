<?php
class Balance {
    private $events = [];

    public function __construct() {
        //
    }

    public function read() {
        return $this->events;
    }
}