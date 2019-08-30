<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class MicroPostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

//        for($i=0;$i<10;$i++){
//            $micropost = new MicroPost();
//            $micropost->setText('Some randm text'.rand(1,100));
//            $micropost->setTime(new \DateTime(- rand(1,100).'day'));
//            $manager->persist($micropost);
//
//
//        }
        $manager->flush();
    }
}
