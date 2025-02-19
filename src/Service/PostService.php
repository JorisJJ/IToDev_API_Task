<?php

namespace App\Service;

use App\DTO\Payload\PaginationDTO;
use App\DTO\Payload\PostPayloadDTO;
use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostService extends BaseService
{
    private PostRepository $postRepository;

    public function __construct(
        PostRepository         $postRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->postRepository = $postRepository;
        parent::__construct($entityManager);
    }

    public function getAllPaginatedPosts(PaginationDTO $paginationDTO): array
    {
        $query = $this->postRepository->getPaginationQuery();

        $offset = ($paginationDTO->page - 1) * $paginationDTO->limit;
        $query->setFirstResult($offset)->setMaxResults($paginationDTO->limit);
        $paginator = new Paginator($query);

        $posts = [];
        foreach ($paginator as $post) {
            $posts[] = $post;
        }

        return [
            'posts' => $posts,
            'currentPage' => $paginationDTO->page,
            'totalPages' => ceil($paginator->count() / $paginationDTO->limit),
        ];
    }

    public function getPostById(int $id): Post
    {
        $post = $this->postRepository->find($id);
        if (!$post) {
            throw new NotFoundHttpException("Post not found");
        }

        return $post;
    }

    public function createPost(PostPayloadDTO $postCreateDTO): Post
    {
        $post = new Post();
        $post->setTitle($postCreateDTO->title);
        $post->setContent($postCreateDTO->content);
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $post;
    }

    public function updatePost(int $id, PostPayloadDTO $postUpdateDTO): Post
    {
        $post = $this->postRepository->find($id);
        if (!$post) {
            throw new NotFoundHttpException("Post not found");
        }

        $post->setTitle($postUpdateDTO->title);
        $post->setContent($postUpdateDTO->content);
        $this->entityManager->flush();

        return $post;
    }

    public function deletePost(int $id): bool
    {
        $post = $this->postRepository->find($id);
        if (!$post) {
            throw new NotFoundHttpException("Post not found");
        }

        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return true;
    }
}