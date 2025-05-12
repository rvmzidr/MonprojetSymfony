<?php

namespace App\Controller;

use App\Entity\City;
use App\Repository\CityRepository;
use App\Repository\TransportRepository;
use App\Service\GroqCloudService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/transport')]
class TransportController extends AbstractController
{
    private $groqCloudService;

    public function __construct(GroqCloudService $groqCloudService)
    {
        $this->groqCloudService = $groqCloudService;
    }

    #[Route('/', name: 'app_transport_index', methods: ['GET'])]
    public function index(TransportRepository $transportRepository): Response
    {
        return $this->render('transport/list.html.twig', [
            'transports' => $transportRepository->findAll(),
        ]);
    }

    #[Route('/city/{id}', name: 'app_transport_by_city', methods: ['GET'])]
    public function transportsByCity(City $city): Response
    {
        return $this->render('transport/list.html.twig', [
            'transports' => $city->getTransports(),
            'city' => $city,
        ]);
    }

    #[Route('/route', name: 'app_transport_route', methods: ['GET'])]
    public function transportRoute(Request $request, CityRepository $cityRepository): Response
    {
        $fromCityId = $request->query->get('from');
        $toCityId = $request->query->get('to');
        
        $fromCity = $fromCityId ? $cityRepository->find($fromCityId) : null;
        $toCity = $toCityId ? $cityRepository->find($toCityId) : null;
        
        $transportInfo = [];
        
        if ($fromCity) {
            $transportInfo = $this->groqCloudService->getTransportInfo(
                $fromCity->getName(),
                $toCity ? $toCity->getName() : null
            );
        }
        
        return $this->render('transport/route.html.twig', [
            'from_city' => $fromCity,
            'to_city' => $toCity,
            'transport_info' => $transportInfo,
            'cities' => $cityRepository->findAll(),
        ]);
    }
}