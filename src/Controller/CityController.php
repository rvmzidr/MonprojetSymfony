<?php
// src/Controller/CityController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;

class CityController extends AbstractController
{
    #[Route('/city', name: 'app_city_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $cities = $entityManager->getRepository(City::class)->findAll();

        return $this->render('city/list.html.twig', [
            'cities' => $cities,
        ]);
    }

    #[Route('/city/{id}', name: 'app_city_show', requirements: ['id' => '\d+'])]
    public function show(EntityManagerInterface $entityManager, int $id): Response
    {
        $city = $entityManager->getRepository(City::class)->find($id);

        if (!$city) {
            throw $this->createNotFoundException('La ville demandée n\'existe pas');
        }

        return $this->render('city/details.html.twig', [
            'city' => $city,
        ]);
    }
}