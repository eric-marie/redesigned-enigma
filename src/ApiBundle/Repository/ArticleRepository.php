<?php

namespace ApiBundle\Repository;

use ApiBundle\Entity\Article;
use Doctrine\ORM\EntityRepository;

/**
 * Class ArticleRepository
 * @package ApiBundle\Repository
 */
class ArticleRepository extends EntityRepository
{
    /**
     * Retourne tous les Article avec leurs Comment
     *
     * @return array
     */
    public function findAllWithComments()
    {
        $dql = <<<DQL
SELECT a, c 
FROM ApiBundle:Article a
LEFT JOIN a.comments c
ORDER BY a.id ASC, c.id ASC
DQL;
        return $this
            ->getEntityManager()
            ->createQuery($dql)
            ->getResult();
    }

    /**
     * Retourne un Article trouvé par son Id avec ses Comment
     *
     * @param $id
     * @return Article
     */
    public function findOneByIdWithComments($id)
    {
        $dql = <<<DQL
SELECT a, c 
FROM ApiBundle:Article a
LEFT JOIN a.comments c
WHERE a.id = :id
ORDER BY a.id ASC, c.id ASC
DQL;
        return $this
            ->getEntityManager()
            ->createQuery($dql)
            ->setParameters([
                'id' => $id
            ])
            ->getSingleResult();
    }
}