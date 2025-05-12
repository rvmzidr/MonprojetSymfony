<?php

namespace App\Controller;

use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PagesController extends AbstractController
{
    #[Route('/pages', name: 'app_pages')]
    public function index(Security $security, CityRepository $cityRepository): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_login');
        }

        // Fetch featured cities
        $featuredCities = $cityRepository->findBy(['isFeatured' => true]);

        return $this->render('pages/index.html.twig', [
            'featuredCities' => $featuredCities,
        ]);
    }
}
