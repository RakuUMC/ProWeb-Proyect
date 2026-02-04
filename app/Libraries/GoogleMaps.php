<?php

namespace App\Libraries;

use CodeIgniter\Config\Services;

class GoogleMaps
{
    private $apiKey;
    private $baseUrl = 'https://routes.googleapis.com/directions/v2:computeRoutes';

    public function __construct()
    {
        $this->apiKey = getenv('GOOGLE_MAPS_API_KEY');
    }

    public function getRouteAttributes($origin, $destination)
    {
        if (empty($this->apiKey) || $this->apiKey === 'YOUR_API_KEY_HERE') {
            return [
                'error' => 'La API Key no está configurada',
                'duration' => 'N/A',
                'distance' => 'N/A'
            ];
        }

        $client = Services::curlrequest();

        $body = [
            'origin' => [
                'address' => $origin
            ],
            'destination' => [
                'address' => $destination
            ],
            'travelMode' => 'TWO_WHEELER', // Pizza delivery usually motor
            'routingPreference' => 'TRAFFIC_AWARE',
            'computeAlternativeRoutes' => false,
            'routeModifiers' => [
                'avoidTolls' => false,
                'avoidHighways' => false,
                'avoidFerries' => false
            ],
            'languageCode' => 'es-419',
            'units' => 'METRIC'
        ];

        try {
            $response = $client->request('POST', $this->baseUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Goog-Api-Key' => $this->apiKey,
                    'X-Goog-FieldMask' => 'routes.duration,routes.distanceMeters,routes.staticDuration'
                ],
                'json' => $body,
                'http_errors' => false 
            ]);

            $json = json_decode($response->getBody(), true);

            if (isset($json['routes'][0])) {
                $route = $json['routes'][0];
                $durationSeconds = isset($route['duration']) ? (int)str_replace('s', '', $route['duration']) : 0;
                $distanceMeters = isset($route['distanceMeters']) ? $route['distanceMeters'] : 0;

                return [
                    'duration_text' => $this->formatDuration($durationSeconds),
                    'distance_text' => $this->formatDistance($distanceMeters),
                    'duration_seconds' => $durationSeconds,
                    'distance_meters' => $distanceMeters
                ];
            } else {
                return ['error' => 'No route found', 'details' => $json];
            }

        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function formatDuration($seconds)
    {
        $minutes = round($seconds / 60);
        return $minutes . ' min';
    }

    private function formatDistance($meters)
    {
        if ($meters >= 1000) {
            return number_format($meters / 1000, 1) . ' km';
        }
        return $meters . ' m';
    }
}
