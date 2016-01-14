<?php
// src/OC/PlatformBundle/Entity/AdvertRepository.php

namespace OC\PlatformBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdvertRepository extends EntityRepository
{
  public function getAdverts($page, $nbPerPage)
  {
    $query = $this->createQueryBuilder('a')
      //jointure sur l'attribut image
      ->leftJoin('a.image', 'i')
      ->addSelect('i')
      //jointure sur l'attribut catégories
      ->leftJoin('a.categories', 'c')
      ->addSelect('c')
      ->orderBy('a.date', 'DESC')
      ->getQuery()
    ;

    $query
      //définition de l'annonce à partir de laquelle on commence la liste
      ->setFirstResult(($page-1) * $nbPerPage)
      //ainsi que le nombre d'annonce à afficher sur une page
      ->setMaxResults($nbPerPage)
    ;

    return new Paginator($query, true);
  }

  public function getAdvertWithCategories(array $categoryNames)
  {
    $qb = $this
      ->createQueryBuild('a')
      ->join('a.categories', 'c')
      ->addSelect('c');

    //on filtre sur le nom des catégories à l'aide d'un IN
    $qb->where($qb->expr()->in('c.name', $categoryNames));

    return $qb
      ->getQuery()
      ->getResult();
  }
  public function myFind()
  {
    $qb = $this->createQueryBuilder('a');

    // On peut ajouter ce qu'on veut avant
    $qb
      ->where('a.author = :author')
      ->setParameter('author', 'Marine')
    ;

    // On applique notre condition sur le QueryBuilder
    $this->whereCurrentYear($qb);

    // On peut ajouter ce qu'on veut après
    $qb->orderBy('a.date', 'DESC');

    return $qb
      ->getQuery()
      ->getResult()
    ;
  }

  public function myFindAll()
  {
    // Méthode 1 : en passant par l'EntityManager
    $queryBuilder = $this->_em->createQueryBuilder()
      ->select('a')
      ->from($this->_entityName, 'a')
    ;
    // Dans un repository, $this->_entityName est le namespace de l'entité gérée
    // Ici, il vaut donc OC\PlatformBundle\Entity\Advert

    // Méthode 2 : en passant par le raccourci (je recommande)
    $queryBuilder = $this->createQueryBuilder('a');

    // On n'ajoute pas de critère ou tri particulier, la construction
    // de notre requête est finie

    // On récupère la Query à partir du QueryBuilder
    $query = $queryBuilder->getQuery();

    // On récupère les résultats à partir de la Query
    $results = $query->getResult();

    // On retourne ces résultats
    return $results;
  }
  
  
  public function myFindOne($id)
  {
    $qb = $this->createQueryBuilder('a');

    $qb
      ->where('a.id = :id')
      ->setParameter('id', $id)
    ;

    return $qb
      ->getQuery()
      ->getResult()
    ;
  }
   
  public function findByAuthorAndDate($author, $year)
  {
    $qb = $this->createQueryBuilder('a');

    $qb->where('a.author = :author')
         ->setParameter('author', $author)
       ->andWhere('a.date < :year')
         ->setParameter('year', $year)
       ->orderBy('a.date', 'DESC')
    ;

    return $qb
      ->getQuery()
      ->getResult()
    ;
  }
  
  public function whereCurrentYear(QueryBuilder $qb)
  {
    $qb
      ->andWhere('a.date BETWEEN :start AND :end')
      ->setParameter('start', new \Datetime(date('Y').'-01-01'))  // Date entre le 1er janvier de cette année
      ->setParameter('end',   new \Datetime(date('Y').'-12-31'))  // Et le 31 décembre de cette année
    ;
  }
  public function getPublishedQueryBuilder()
  {
    return $this
      ->createQueryBuilder('a')
      ->where('a.published = :published')
      ->setParameter('published', true)
    ;
  }
}