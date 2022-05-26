<?php

namespace App\Controller;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/home", name="app_homepage")
     */
    public function home(EntityManagerInterface $em): Response
    {
        $recipes = $em->getRepository(Recipe::class)->findLatestRecipe();

        return $this->render('main/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }
}
