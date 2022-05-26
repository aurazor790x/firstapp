<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em  = $em;
    }

    /**
     * @Route("/recipe", name="app_recipe")
     */
    public function index(): Response
    {
        $recipes = $this->em->getRepository(Recipe::class)->findAll();
        return $this->render('recipe/recipes.html.twig', [
            'recipes' => $recipes,
        ]);
    }



    /**
    * @Route("/recipe/new",name="recipe_create")
    * @Route("/recipe/{id}/edit", name="recipe_edit")
    */
     public function form(Recipe $recipe = null, Request $request,EntityManagerInterface $manager){
        if(!$recipe){
            $recipe = new Recipe();
        }

        $form = $this->createForm(RecipeType::class,$recipe);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
//            if(!$recipe->getId()){
//                $recipe->setCreatedAt(new \DateTimeImmutable());
//            }

            $manager->persist($recipe);
            $manager->flush();

            return $this->redirectToRoute('recipe_show',['id'=>$recipe->getId()]);
        }

        return $this->render('recipe/create.html.twig',[
            'formRecipe' => $form->createView(),
            'editMode' => $recipe->getId() !== null
        ]);

    }

    /**
     * @Route("/recipe/{id}/delete", name="recipe_delete")
     */
     public function delete(Recipe $recipe,EntityManagerInterface $em){
        $em->remove($recipe);
        $em->flush();
         return $this->render("recipe/delete.html.twig", [
             "recipe" => $recipe
         ]);
     }



    /**
     * @Route("/recipe/{id}", name="recipe_show")
     */
    public function show(Recipe $recipe){
        // $repo = $this->getDoctrine()->getRepository(Article::class);
        // $article = $repo->find($id);

        return $this->render('recipe/show.html.twig',[
            'recipe' => $recipe
        ]);
    }
}
