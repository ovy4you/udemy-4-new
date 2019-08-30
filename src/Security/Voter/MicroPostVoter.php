<?php

namespace App\Security\Voter;

use App\Entity\MicroPost;
use App\Entity\ProfileUser;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MicroPostVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';
    /**
     * @var AccessDecisionManagerInterface
     */
    private $accessDecisionManager;

    public function __construct(AccessDecisionManagerInterface $accessDecisionManager)
    {
        $this->accessDecisionManager = $accessDecisionManager;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\MicroPost;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if ($this->accessDecisionManager->decide($token, [ProfileUser::ROLE_ADMIN])) {
            return true;
        }
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var MicroPost $microPost */
        $microPost = $subject;
        return $microPost->getUser() === $user;

    }
}
