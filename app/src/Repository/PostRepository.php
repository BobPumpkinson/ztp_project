<?php

/**
 * Post repository.
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class PostRepository.
 *
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->createQueryBuilder('post')
            ->select(
                'partial post.{id, createdAt, updatedAt, title}',
                'partial category.{id, title}',
            )
            ->join('post.category', 'category');
    }

    /**
     * Count posts by category.
     *
     * @param Category $category Category
     *
     * @return int Number of posts in category
     */
    public function countByCategory(Category $category): int
    {
        $qb = $this->createQueryBuilder('post');

        return $qb->select($qb->expr()->countDistinct('post.id'))
            ->where('post.category = :category')
            ->setParameter(':category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Count posts by tag.
     *
     * @param Tag $tag Tag
     *
     * @return int Number of posts in tag
     */
    public function countByTag(Tag $tag): int
    {
        $qb = $this->createQueryBuilder('post');

        return $qb->select($qb->expr()->countDistinct('post.id'))
            ->where('post.tag = :tag')
            ->setParameter(':tag', $tag)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Save entity.
     *
     * @param Post $post Post entity
     */
    public function save(Post $post): void
    {
        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete entity.
     *
     * @param Post $post Post entity
     */
    public function delete(Post $post): void
    {
        $this->getEntityManager()->remove($post);
        $this->getEntityManager()->flush();
    }

    /**
     * Find one with relations.
     *
     * @param int $id Id
     *
     * @return Post|null Post
     */
    public function findOneWithRelations(int $id): ?Post
    {
        return $this->createQueryBuilder('post')
            ->leftJoin('post.category', 'category')->addSelect('category')
            ->leftJoin('post.tags', 'tags')->addSelect('tags')
            ->where('post.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find comments by post.
     *
     * @param int $postId Post id
     *
     * @return array Array
     */
    public function findCommentsByPost(int $postId): array
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('comment', 'author')
            ->from('App\Entity\Comment', 'comment')
            ->leftJoin('comment.author', 'author')
            ->where('comment.post = :postId')
            ->setParameter('postId', $postId)
            ->orderBy('comment.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
