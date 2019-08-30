<?php

namespace App\Controller;

use App\Entity\ProfileUser;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/following")
 */
class FollowingController extends AbstractController
{
    /**
     * @Route("/follow/{id}", name="following_follow")
     */
    public function follow(ProfileUser $userToFollow)
    {
        /** @var ProfileUser $currentUser */
        $currentUser = $this->getUser();
        $currentUser->getFollowing()->add($userToFollow);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute(
            'micro_post_user',
            ['email' => $userToFollow->getEmail()]
        );
    }

    /**
     * @Route("/unfollow/{id}", name="following_unfollow")
     */
    public function unfollow(ProfileUser $userToUnFollow)
    {
        /** @var ProfileUser $currentUser */
        $currentUser = $this->getUser();
        $currentUser->getFollowing()->remove($userToUnFollow);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('micro_post_user', ['email' => $userToUnFollow->getEmail()]);
    }
}
