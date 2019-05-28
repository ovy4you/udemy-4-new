<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{

    /**
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em->persist($blogPost);
        $em->flush();
        return $this->json($blogPost);
    }


    /**
     * @Route("/{page}", name="blog_list",defaults={"page":1},requirements={"page"="\d+"})
     */
    public function list($page, Request $request, EntityManagerInterface $em)
    {
        $blogPostRepository = $em->getRepository(BlogPost::class);
        $items = $blogPostRepository->findAll();
        return new JsonResponse(
            ['page' => $page,
                'data' => array_map(function (BlogPost $item) {
                    return $this->generateUrl('blog_by_id', ['id' => $item->getId()]);
                }, $items)
            ]);
    }

    /**
     * @Route("/post/{id}", name="blog_by_id",requirements={"id"="\d+"},methods={"GET"})
     */
    public function post(BlogPost $post)
    {
        return $this->json($post);

    }

    /**
     * @Route("/{slug}", name="blog_by_slug")
     */
    public function postBySlug(BlogPost $post)
    {
        return $this->json($post);
    }

    /**
     * @Route("/post/{id}", name="blog_delete",requirements={"id"="\d+"},methods={"DELETE"})
     */
    public function delete(BlogPost $post,EntityManagerInterface $em)
    {
        $em->remove($post);
        $em->flush();
        return $this->json(null,Response::HTTP_NO_CONTENT);

    }

}
