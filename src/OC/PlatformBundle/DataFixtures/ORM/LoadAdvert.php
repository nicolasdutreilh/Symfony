<?php
// src/OC/PlatformBundle/DataFixtures/ORM/LoadAdvert.php

namespace OC\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Advert;

class LoadAdvert extends AbstractFixture implements OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    //annonces à ajouter
    //annonce d'Alexandre
    $advertAlex = new Advert();
    $advertAlex->setImage($this->getReference('image-jdr'));
    $advertAlex->setTitle('Recherche développeur Symfony2');
    $advertAlex->setAuthor('Alexandre');
    $advertAlex->setContent('Nous recherchons un développeur');
    $advertAlex->setSlug('recherche-developpeur');
    $advertAlex->setDate(new \Datetime());

    //annonce d'Hugo
    $advertHugo = new Advert();
    $advertHugo->setImage($this->getReference('image-af'));
    $advertHugo->setTitle('Mission de webmaster');
    $advertHugo->setAuthor('Hugo');
    $advertHugo->setContent('Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…');
    $advertHugo->setSlug('mission-webmaster');
    $advertHugo->setDate(new \Datetime());

    //annonce de Mathieu
    $advertMat = new Advert();
    $advertMat->setImage($this->getReference('image-pa'));
    $advertMat->setTitle('Offre de stage webdesigner');
    $advertMat->setAuthor('Mathieu');
    $advertMat->setContent('Nous proposons un poste pour webdesigner. Blabla…');
    $advertMat->setSlug('stage-webdesigner');
    $advertMat->setDate(new \Datetime());

    // On la persiste
    $manager->persist($advertAlex);
    $manager->persist($advertHugo);
    $manager->persist($advertMat);

    // On déclenche l'enregistrement de toutes les catégories
    $manager->flush();
  }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}