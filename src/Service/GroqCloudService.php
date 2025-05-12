<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GroqCloudService
{
    private $httpClient;
    private $apiKey;

    public function __construct(HttpClientInterface $httpClient, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
    }

    /**
     * Obtenir des informations sur une ville touristique
     */
    public function getCityInfo(string $cityName): array
    {
        $prompt = "Fournir des informations détaillées sur la ville touristique de $cityName en Tunisie. 
                  Inclure une description, les principales attractions, et des informations culturelles.
                  Formatez la réponse en JSON avec les clés: description, attractions (array), culture.";

        return $this->makeRequest($prompt);
    }

    /**
     * Obtenir des informations sur les moyens de transport
     */
    public function getTransportInfo(string $fromCity, string $toCity = null): array
    {
        $prompt = "Quels sont les moyens de transport disponibles ";
        
        if ($toCity) {
            $prompt .= "pour voyager de $fromCity à $toCity en Tunisie? ";
        } else {
            $prompt .= "dans et autour de $fromCity en Tunisie? ";
        }
        
        $prompt .= "Inclure les types de transport (bus, train, taxi, etc.), les prix approximatifs, et la durée.
                   Formatez la réponse en JSON avec un tableau de moyens de transport, chacun ayant les clés: 
                   type, description, prix_approximatif, durée_approximative.";

        return $this->makeRequest($prompt);
    }

    /**
     * Obtenir des recommandations d'attractions
     */
    public function getAttractionRecommendations(string $cityName, array $preferences = []): array
    {
        $preferencesText = !empty($preferences) ? "avec un intérêt particulier pour " . implode(", ", $preferences) : "";
        
        $prompt = "Recommandez les 5 meilleures attractions touristiques à visiter à $cityName en Tunisie $preferencesText.
                  Pour chaque attraction, fournissez le nom, une description détaillée, l'adresse et pourquoi elle vaut la peine d'être visitée.
                  Formatez la réponse en JSON avec un tableau d'attractions, chacune ayant les clés: 
                  nom, description, adresse, raison_de_visite, note_sur_5.";

        return $this->makeRequest($prompt);
    }

    /**
     * Faire une requête à l'API GroqCloud
     */
    private function makeRequest(string $prompt): array
    {
        $response = $this->httpClient->request('POST', 'https://api.groq.com/openai/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'llama3-70b-8192',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Vous êtes un expert en tourisme tunisien. Fournissez des informations précises et détaillées sur les villes touristiques et les moyens de transport en Tunisie. Répondez toujours en français et au format JSON.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 2000
            ],
        ]);

        $data = $response->toArray();
        
        // Extraire le contenu JSON de la réponse
        $content = $data['choices'][0]['message']['content'] ?? '';
        
        // Essayer de parser le JSON
        try {
            // Trouver le début et la fin du JSON dans la réponse
            $jsonStart = strpos($content, '{');
            $jsonEnd = strrpos($content, '}');
            
            if ($jsonStart !== false && $jsonEnd !== false) {
                $jsonContent = substr($content, $jsonStart, $jsonEnd - $jsonStart + 1);
                return json_decode($jsonContent, true) ?: ['error' => 'Format JSON invalide', 'raw_content' => $content];
            }
            
            return ['error' => 'Aucun JSON trouvé dans la réponse', 'raw_content' => $content];
        } catch (\Exception $e) {
            return ['error' => 'Erreur lors du traitement de la réponse', 'message' => $e->getMessage(), 'raw_content' => $content];
        }
    }
}