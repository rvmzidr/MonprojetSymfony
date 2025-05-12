<?php
// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\City;
use App\Entity\Attraction;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer les villes mises en avant
        $featuredCities = $entityManager->getRepository(City::class)->findBy(
            ['isFeatured' => true],
            ['name' => 'ASC'],
            3
        );
        
        // Récupérer les attractions populaires
        $popularAttractions = $entityManager->getRepository(Attraction::class)->findBy(
            ['isPopular' => true],
            ['name' => 'ASC'],
            4
        );

        return $this->render('pages/index.html.twig', [
            'featuredCities' => $featuredCities,
            'popularAttractions' => $popularAttractions,
        ]);
    }
}