<?php


namespace App\Services;


use GuzzleHttp\Client;
use Throwable;

class GeoLocationService
{
    public static function distanceBetweenTowCoordinates(
        float $latitudeA,
        float $longitudeA,
        float $latitudeB,
        float $longitudeB
    ): float
    {
        $latA = deg2rad($latitudeA);
        $longA = deg2rad($longitudeA);
        $latB = deg2rad($latitudeB);
        $longB = deg2rad($longitudeB);
        $a = cos($latA) * cos($latB) * cos($longB - $longA);
        $b = sin($latA) * sin($latB);
        return 6372 * acos($a + $b);
    }

    public static function getAddressByZipcode(string $zipcode): ?array
    {
        try {
            $zipcode = preg_replace('/\D/', '', $zipcode);
            $token = env('CEP_ABERTO_TOKEN', '');
            $client = new Client([
                'base_uri' => env('CEP_ABERTO_URL', ''),
                'headers' => ['Authorization' => "Token token={$token}"]
            ]);
            $request = $client->get("/api/v3/cep?cep={$zipcode}");
            $response = (string)$request->getBody();
            return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $th) {
            return null;
        }
    }
}