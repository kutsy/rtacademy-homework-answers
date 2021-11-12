<?php

namespace App\Controller;

use App\Entity\PostCategory;
use App\Form\PostCategoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{
    #[Route('/admin/category/create', name: 'admin_category_create', methods: ['GET','POST'])]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $category       = new PostCategory();
        $category_form  = $this->createForm( PostCategoryFormType::class, $category );

        $category_form->handleRequest( $request );

        if( $category_form->isSubmitted() && $category_form->isValid() )
        {
            $entityManager->persist( $category );
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Category "' . $category->getTitle() . '" has added successfully.'
            );

            return $this->redirectToRoute( 'homepage' );
        }

        return $this->render(
            'admin_category/create.html.twig',
            [
                'category_form' => $category_form->createView(),
            ]
        );
    }
}
