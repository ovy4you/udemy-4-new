<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\ProfileUser;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/micropost")
 */
class MicroPostController extends AbstractController
{
    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(
        MicroPostRepository $microPostRepository,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        RouterInterface $router,
        FlashBagInterface $flashBag
    )
    {
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/", name="micro_post")
     */
    public function index()
    {
        return $this->render('micro_post/index.html.twig', [
            'posts' => $this->microPostRepository->findAll(),
        ]);
    }

    /**
     * @Route("/add", name="micro_post_add")
     */
    public function add(Request $request)
    {
        $user = $this->getUser();
        $microPost = new MicroPost();
        $microPost->setUser($user);

        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('micro_post'));
        }


        return $this->render('micro_post/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="micro_post_edit")
     */
    public function edit(MicroPost $microPost, Request $request)
    {
        $this->denyAccessUnlessGranted('edit', $microPost);
        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();
        }

        return $this->render('micro_post/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="micro_post_delete")
     * @Security("is_granted('delete',microPost)")
     */
    public function delete(MicroPost $microPost)
    {
        $this->entityManager->remove($microPost);
        $this->entityManager->flush();
        $this->flashBag->add('notice', 'micropost was deleted');

        return new RedirectResponse($this->router->generate('micro_post'));
    }

    /**
     * @Route("/{id}", name="micro_post_post")
     */
    public function post(MicroPost $microPost)
    {
        return $this->render('micro_post/post.html.twig', [
            'post' => $microPost,
        ]);
    }

    /**
     * @Route("/user/{id}", name="micro_post_user")
     */
    public function userPosts(ProfileUser $profileUser)
    {
        return $this->render('micro_post/user.html.twig', [
            'posts' => $this->microPostRepository->findBy(['user' => $profileUser]),
            'user' => $profileUser
        ]);
    }
}
