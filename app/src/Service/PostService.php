<?php

/**
 * Post service.
 */

namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class PostService.
 */
class PostService implements PostServiceInterface
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param PostRepository     $postRepository Post repository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(private readonly PostRepository $postRepository, private readonly PaginatorInterface $paginator)
    {
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->postRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['post.id', 'post.createdAt', 'post.updatedAt', 'post.title'],
                'defaultSortFieldName' => 'post.updatedAt',
                'defaultSortDirection' => 'desc',
            ]
        );
    }

    /**
     * Save entity.
     *
     * @param Post $post Post entity
     */
    public function save(Post $post): void
    {
        $this->postRepository->save($post);
    }

    /**
     * Delete entity.
     *
     * @param Post $post Post entity
     */
    public function delete(Post $post): void
    {
        $this->postRepository->delete($post);
    }

    /**
     * Find one by id.
     *
     * @param int $id Id
     *
     * @return Post $post Post entity
     */
    public function findOneById(int $id): ?Post
    {
        return $this->postRepository->findOneWithRelations($id);
    }

    /**
     * Find one with relations.
     *
     * @param int $id Id
     *
     * @return Post $post Post entity
     */
    public function findOneWithRelations(int $id): ?Post
    {
        return $this->postRepository->findOneWithRelations($id);
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
        return $this->postRepository->findCommentsByPost($postId);
    }
}
