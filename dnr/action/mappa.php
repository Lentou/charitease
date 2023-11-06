<?php

class mappa {

    public static function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        $R = 6371; // Radius of the Earth in kilometers
        $dLat = deg2rad($lat2 - $lat1); // Convert degrees to radians
        $dLon = deg2rad($lon2 - $lon1); // Convert degrees to radians
        $a =
            sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $R * $c;
      
        return round($distance, 2);
    }

    public static function geocodeAddress($address) {
        $geocode_url = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($address);
    
        // Set up a context for the HTTP request
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
            ],
        ]);
    
        // Perform the HTTP request using file_get_contents
        $response = file_get_contents($geocode_url, false, $context);
    
        if ($response !== false) {
            $data = json_decode($response, true);
    
            if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
                // Extract the latitude and longitude from the response
                $lat = $data[0]['lat'];
                $lng = $data[0]['lon'];
    
                return ['lat' => $lat, 'lng' => $lng];
            }
        }
    
        // Handle errors or invalid addresses here
        return null;
    }
    
    
}

?>