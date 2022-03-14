<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post', name: 'post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(Post $post, PostRepository $postRepository): Response{
        // create the show view
        return $this->render('post/show.html.twig',[
                'post' => $post
        ]);
    }

    #[Route('/create')]
    public function create(Request $request, ManagerRegistry $doctrine){
        // create a new post
        $post = new Post();
        $post->setTitle('This is going to be a title :)');

        // entity manager
        $em = $doctrine->getManager();

        $em->persist($post);
        $em->flush();

        // return a response
        return new Response('Post was created .');
    }
}
