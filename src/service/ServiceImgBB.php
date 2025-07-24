<?php

namespace App\src\service;

class ServiceImgBB
{
    private string $apiKey;
    private string $uploadUrl = 'https://api.imgbb.com/1/upload';

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function uploadImage(string $localPath): ?string
    {
        if (!file_exists($localPath)) 
        return 'https://ibb.co/b50DMtX5';

        $imageData = base64_encode(file_get_contents($localPath));
        $expiration = 600;

        $curl = curl_init("{$this->uploadUrl}?expiration={$expiration}&key={$this->apiKey}");
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ['image' => $imageData],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($response, true);

        return $result['data']['url'] ?? null;
    }
}
