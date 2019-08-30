<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\ProfileUser;
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
//        $this->loadUser($manager);
//        $this->loadBlogPosts($manager);
//        $this->loadComment($manager);
        $this->loadProfileUser($manager);
        $this->loadMicroPosts($manager);
        $this->loadProfileUser1($manager);
        $this->loadMicroPosts1($manager);
        $manager->flush();
    }

//    public function loadBlogPosts(ObjectManager $manager)
//    {
////        $user = $this->getReference('admin');
////
////        for ($i = 0; $i < 40; $i++) {
////            $blogPost = new BlogPost();
////            $blogPost->setAuthor($user)
////                ->setSlug('slug' . $i)
////                ->setTitle('title' . $i)
////                ->setText('blog-post' . $i)
////                ->setPublished(new \DateTime());
////            $this->addReference('blog_post' . $i, $blogPost);
////            $manager->persist($blogPost);
////        }
//       // $manager->flush();
//    }

//    public function loadComment(ObjectManager $manager)
//    {
//
////        $user = $this->getReference('admin');
////
////        for ($j = 0; $j < 40; $j++) {
////            $blogPost = $this->getReference('blog_post' . $j);
////            $comment = new Comment();
////            $comment->setContent('comment' . $j)
////                ->setAuthor($user)
////                ->setBlogPost($blogPost)
////                ->setPublished(new \DateTime());
////            $manager->persist($comment);
////        }
//      //  $manager->flush();
//    }


//    public function loadUser(ObjectManager $manager)
//    {
////        $user = new User();
////
////        $user->setEmail('ovy_4_you@yahoo.com')
////            ->setName('ocvidiu')
////            ->setUsername('admin')
////            ->setPassword($this->passwordEncoder->encodePassword(
////                $user,
////                '123'
////            ));
////
////        $this->addReference('admin', $user);
////
////        $manager->persist($user);
////        $manager->flush();
//    }


    public function loadMicroPosts(ObjectManager $manager)
    {
        if ($this->getReference('ovidiu')) {
            for ($i = 0; $i < 10; $i++) {
                $micropost = new MicroPost();
                $micropost->setText('Some randm text' . rand(1, 100));
                $micropost->setTime(new \DateTime(-rand(1, 100) . 'day'));
                $micropost->setUser($this->getReference('ovidiu'));
                $manager->persist($micropost);


            }
        }
    }

    public function loadMicroPosts1(ObjectManager $manager)
    {
        if ($this->getReference('octavian')) {
            for ($i = 0; $i < 10; $i++) {
                $micropost = new MicroPost();
                $micropost->setText('Some randm text' . rand(1, 100));
                $micropost->setTime(new \DateTime(-rand(1, 100) . 'day'));
                $micropost->setUser($this->getReference('octavian'));
                $manager->persist($micropost);


            }
        }
    }

    public function loadProfileUser(ObjectManager $manager)
    {
        $user = new ProfileUser();

        $user->setEmail('ovy_4_you@yahoo.com')
            ->setFullname('ovidiu ciuca')
            ->setUsername('admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                '123'
            ));

        $this->addReference('ovidiu', $user);
        $manager->persist($user);
        $manager->flush();
    }

    public function loadProfileUser1(ObjectManager $manager)
    {
        $user = new ProfileUser();

        $user->setEmail('octavian@yahoo.com')
            ->setFullname('octavian ciuca')
            ->setUsername('octavian')
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                '123'
            ));

        $this->addReference('octavian', $user);
        $manager->persist($user);
        $manager->flush();
    }
}
