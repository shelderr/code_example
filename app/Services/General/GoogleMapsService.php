<?php

namespace App\Services\General;

use App\Exceptions\Application\ApplicationException;
use App\Repositories\General\CountryRepository;

class GoogleMapsService
{
    /**
     * @param string $location
     *
     * @return mixed
     * @throws \App\Exceptions\Application\ApplicationException
     */
    public function searchLocation(string $location): mixed
    {
        $key = config('services.googleMaps.key');

        if (is_null($key)) {
            throw new ApplicationException('google maps key missing');
        }


        return \Http::get("https://maps.google.com/maps/api/geocode/json?address=$location&key=$key&language='english'")->json();
    }
}
