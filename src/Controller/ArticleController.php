<?php

namespace App\Controller;

use App\Service\Greeting;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @var Greeting
     */
    private $greeting;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(Greeting $greeting, SessionInterface $session, RouterInterface $router)
    {
        $this->greeting = $greeting;
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @Route("/", name="article_index")
     */
    public function index()
    {

        return $this->render('article/index.html.twig', [
            'posts' => $this->session->get('posts'),
        ]);
    }

    /**
     * @Route("/add", name="article_add")
     */
    public function add()
    {
        $posts = $this->session->get('posts');
        $posts[uniqid()] = [
            'title' => 'A random title' . rand(1, 100),
            'text' => 'Some random text' . rand(1, 100),
            'date' => new \DateTime(),
            'price' => rand(1,500),
        ];

        $this->session->set('posts', $posts);

        return new RedirectResponse($this->router->generate('article_index'));
    }

    /**
     * @Route("/show/{id}", name="article_show")
     */
    public function show($id)
    {
        $posts = $this->session->get('posts');

        if (empty($posts) || !isset($posts[$id])) {
            throw new NotFoundHttpException('Post not found');
        }

        return $this->render('article/post.html.twig', [
            'id' => $id,
            'post' => $posts[$id],
        ]);

    }

}
