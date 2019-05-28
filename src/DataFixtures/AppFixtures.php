<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class AppFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUser($manager);
        $this->loadBlogPosts($manager);
        $this->loadComment($manager);
        $manager->flush();
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
        $user = $this->getReference('admin');

        for ($i = 0; $i < 40; $i++) {
            $blogPost = new BlogPost();
            $blogPost->setAuthor($user)
                ->setSlug('slug' . $i)
                ->setTitle('title' . $i)
                ->setText('blog-post' . $i)
                ->setPublished(new \DateTime());
            $this->addReference('blog_post'.$i, $blogPost);
            $manager->persist($blogPost);
        }
        $manager->flush();
    }

    public function loadComment(ObjectManager $manager)
    {

        $user = $this->getReference('admin');

        for ($j = 0; $j < 40; $j++) {
            $blogPost = $this->getReference('blog_post'.$j);
            $comment = new Comment();
            $comment->setContent('comment' . $j)
                ->setAuthor($user)
                ->setBlogPost($blogPost)
                ->setPublished(new \DateTime());
            $manager->persist($comment);
        }
        $manager->flush();
    }


    public function loadUser(ObjectManager $manager)
    {
        $user = new User();

        $user->setEmail('ovy_4_you@yahoo.com')
            ->setName('ocvidiu')
            ->setUsername('admin')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                '123'
            ));

        $this->addReference('admin', $user);

        $manager->persist($user);
        $manager->flush();
    }
}
