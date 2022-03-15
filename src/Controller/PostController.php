<?php

namespace App\Controller;

use App\Form\PostType;
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

    #[Route('/show/{id}', name: 'show')]
    public function show(Post $post, PostRepository $postRepository): Response{
        // create the show view
        return $this->render('post/show.html.twig',[
                'post' => $post
        ]);
    }

    #[Route('/create',name: 'create')]
    public function create(Request $request, ManagerRegistry $doctrine){
        // create a new post
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // entity manager
            $em = $doctrine->getManager();
            $em->persist($post);
            $em->flush();
            $this->addFlash('success','Post was added.');
            return $this->redirect($this->generateUrl('postindex'));
        }

        // return a response
        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/remove/{id}',name: 'remove')]
    public function remove(Post $post, ManagerRegistry $doctrine){
        $em = $doctrine->getManager();
        $em->remove($post);
        $em->flush();
        $this->addFlash('success','Post was removed .');
        return $this->redirect($this->generateUrl('postindex'));
    }
}
