<?php

namespace App\Repository;

use App\Entity\Pelicula;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pelicula|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pelicula|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pelicula[]    findAll()
 * @method Pelicula[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeliculasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
    {
        parent::__construct($registry, Pelicula::class);
        $this->manager = $manager;
    }

    public function savePelicula($nombre, $genero, $descripcion)
    {
        $newPelicula = new Pelicula();
        $newPelicula
            ->setNombre($nombre)
            ->setGenero($genero)
            ->setDescripcion($descripcion);
            $this->manager->persist($newPelicula);
            $this->manager->flush();
    }

    public function updatePelicula(Pelicula $pelicula){
        $this->manager->persist($pelicula);
        $this->manager->flush();

        return $pelicula;
    }

    public function removePelicula(Pelicula $pelicula){
        $this->manager->remove($pelicula);
        $this->manager->flush();

    }
    // /**
    //  * @return Peliculas[] Returns an array of Peliculas objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Peliculas
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
