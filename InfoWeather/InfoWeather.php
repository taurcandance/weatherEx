<?php

namespace InfoWeather;


class InfoWeather
{
    public $response;
    public $error;

    /**
     * infoWeather constructor.
     *
     * @param $response
     * @param $error
     */
    public function __construct($response, $error)
    {
        $this->response = $response;
        $this->error    = $error;
    }
}