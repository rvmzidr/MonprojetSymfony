<?php
namespace App\Controller;

use App\Repository\AttractionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AttractionController extends AbstractController
{
    #[Route('/attractions/{cityId}', name: 'attraction_by_city')]
    public function listAttractionsByCity(AttractionRepository $attractionRepository, int $cityId): Response
    {
        $attractions = $attractionRepository->findByCity($cityId);

        return $this->render('attraction/list.html.twig', [
            'attractions' => $attractions,
        ]);
    }

    #[Route('/popular-attractions', name: 'popular_attractions')]
    public function popularAttractions(AttractionRepository $attractionRepository): Response
    {
        $popularAttractions = $attractionRepository->findPopularAttractions();

        return $this->render('attraction/popular.html.twig', [
            'attractions' => $popularAttractions,
        ]);
    }
}
