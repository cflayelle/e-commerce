<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Product $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Product $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findProductsAvailable(){
        return $this->createQueryBuilder('p')
                    ->andWhere('p.stock > 0')
                    ->getQuery()
                    ->getResult()
        ;
    }

    /**
     * @return QueryBuilder
     */
    public function getProductsAvailableQueryBuilder(): QueryBuilder{
        return $this->createQueryBuilder('p')
                    ->andWhere('p.stock > 0')
                    // ->getQuery()
                    // ->getResult()
        ;
    }

    /**
	 * Retrieve the list of active orders with all their actives packages
	 * @param $page
	 * @return Paginator
	 */
	public function getProductsAvailable($page,$pageSize=10){
		$firstResult = ($page - 1) * $pageSize;

		$queryBuilder = $this->getProductsAvailableQueryBuilder();
		
		// Set the returned page
		$queryBuilder->setFirstResult($firstResult);
		$queryBuilder->setMaxResults($pageSize);
		
		// Generate the Query
		$query = $queryBuilder->getQuery();
		
		// Generate the Paginator
		$paginator = new Paginator($query, true);
		return $paginator;
	}


    // /**
    //  * @return Product[] Returns an array of Product objects
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
    public function findOneBySomeField($value): ?Product
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
