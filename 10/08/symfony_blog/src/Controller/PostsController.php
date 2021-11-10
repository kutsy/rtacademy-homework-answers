<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET', 'HEAD'])]
    public function index( PostRepository $post_repository ): Response
    {
        // Топ запис
        $top_post               = $post_repository->getTopPost();

        // Останні N активних записів
        $latest_posts_paginator = $post_repository->getLatestPosts();

        return $this->render(
            'posts/index.html.twig',
            [
                'top_post'          => $top_post,
                'posts'             => $latest_posts_paginator,
            ]
        );
    }

    #[Route('/post/{id}-{alias}', name: 'post_view', methods: ['GET', 'HEAD'])]
    public function view( int $id, PostRepository $post_repository ): Response
    {
        // Отримання активного запису за ID
        $post = $post_repository->getActivePost( $id );

        if( !$post )
        {
            throw $this->createNotFoundException( 'Post with #' . $id . ' not found' );
        }

        return $this->render(
            'posts/view.html.twig',
            [
                'post' => $post,
            ]
        );
    }
}