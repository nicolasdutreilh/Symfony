<?php
// src/OC/PlatformBundle/DataFixtures/ORM/LoadAdvert.php

namespace OC\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Image;

class LoadImage extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Liste des noms de compétences à ajouter
    //image JDR
    $imageJDR = new Image();
    $imageJDR->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
    $imageJDR->setAlt('Job de rêve');

    //image AF
    $imageAF = new Image();
    $imageAF->setUrl('http://www.superbibi.net/wp-content/uploads/guide-facebook.png');
    $imageAF->setAlt('Annonce facebook');

    //image PA
    $imagePA = new Image();
    $imagePA->setUrl('http://www.jum31.fr/wp-content/uploads/2014/02/Petit-annonce-jumeaux-et-plus-31.jpg');
    $imagePA->setAlt('Petites annonces');
    
    // On la persiste
    $manager->persist($imageJDR);
    $manager->persist($imageAF);
    $manager->persist($imagePA); 
    // On déclenche l'enregistrement de toutes les catégories
    $manager->flush();

    $this->addReference('image-jdr', $imageJDR);
    $this->addReference('image-af', $imageAF);
    $this->addReference('image-pa', $imagePA);
  }
    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }
}