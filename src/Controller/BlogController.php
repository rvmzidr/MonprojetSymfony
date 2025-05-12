<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog/testimonials', name: 'app_blog_testimonials')]
    public function testimonials(): Response
    {
        // Dans une application réelle, ces données viendraient d'une base de données
        $testimonials = [
            [
                'author' => 'Sophie Martin',
                'country' => 'France',
                'content' => 'J\'ai passé deux semaines merveilleuses en Tunisie. Les plages de Hammamet sont magnifiques et les gens sont très accueillants. Je recommande vivement cette destination !',
                'rating' => 5,
                'date' => new \DateTime('2023-06-15'),
                'image' => 'testimonial-1.jpg',
            ],
            [
                'author' => 'John Smith',
                'country' => 'Royaume-Uni',
                'content' => 'La médina de Tunis est fascinante avec ses souks colorés et son architecture traditionnelle. J\'ai adoré me perdre dans ses ruelles et découvrir l\'artisanat local.',
                'rating' => 4,
                'date' => new \DateTime('2023-05-22'),
                'image' => 'testimonial-2.jpg',
            ],
            [
                'author' => 'Maria Rodriguez',
                'country' => 'Espagne',
                'content' => 'Djerba est un véritable paradis ! Ses plages de sable blanc et ses eaux turquoise m\'ont enchantée. J\'ai également apprécié la visite des villages berbères et la découverte de leur culture.',
                'rating' => 5,
                'date' => new \DateTime('2023-07-03'),
                'image' => 'testimonial-3.jpg',
            ],
            [
                'author' => 'Hans Mueller',
                'country' => 'Allemagne',
                'content' => 'Le désert du Sahara est une expérience inoubliable. La nuit passée dans un campement berbère sous les étoiles restera gravée dans ma mémoire. Les dromadaires étaient un peu têtus, mais cela fait partie de l\'aventure !',
                'rating' => 4,
                'date' => new \DateTime('2023-04-18'),
                'image' => 'testimonial-4.jpg',
            ],
            [
                'author' => 'Akiko Tanaka',
                'country' => 'Japon',
                'content' => 'J\'ai été impressionnée par les sites archéologiques de Carthage et le musée du Bardo. La Tunisie possède un patrimoine historique et culturel très riche qui mérite d\'être découvert.',
                'rating' => 5,
                'date' => new \DateTime('2023-03-30'),
                'image' => 'testimonial-5.jpg',
            ],
        ];

        return $this->render('blog/testimonials.html.twig', [
            'testimonials' => $testimonials,
        ]);
    }
}