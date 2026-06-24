<?php

/**
 * Comment repository.
 */

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class CommentRepository.
 *
 * @extends ServiceEntityRepository<Comment>
 */
class CommentRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * Get comments for a post.
     *
     * @param int $postId Post identifier
     *
     * @return QueryBuilder Query builder
     */
    public function queryByPost(int $postId): QueryBuilder
    {
        return $this->createQueryBuilder('comment')
            ->join('comment.post', 'post')
            ->where('post.id = :postId')
            ->setParameter('postId', $postId)
            ->orderBy('comment.createdAt', 'DESC');
    }

    /**
     * Save comment.
     *
     * @param Comment $comment Comment entity
     */
    public function save(Comment $comment): void
    {
        $this->getEntityManager()->persist($comment);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete comment.
     *
     * @param Comment $comment Comment entity
     */
    public function delete(Comment $comment): void
    {
        $this->getEntityManager()->remove($comment);
        $this->getEntityManager()->flush();
    }

    /**
     * Count comments by post.
     *
     * @param Post $post Post entity
     *
     * @return int Number of comments
     */
    public function countByPost(Post $post): int
    {
        $qb = $this->createQueryBuilder('comment');

        return (int) $qb->select($qb->expr()->countDistinct('comment.id'))
            ->where('comment.post = :post')
            ->setParameter('post', $post)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
