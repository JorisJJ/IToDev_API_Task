<?php

namespace App\Controller;

use App\DTO\Payload\PaginationDTO;
use App\DTO\Payload\PostPayloadDTO;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/posts')]
final class PostController extends AbstractController
{
    private PostService $postService;

    public function __construct(
        PostService $postService,
    )
    {
        $this->postService = $postService;
    }

    #[Route('/get-paginated', name: 'get_all_paginated_posts', methods: ['GET'])]
    public function getAllPaginated(#[MapRequestPayload] PaginationDTO $paginationDTO): JsonResponse
    {
        return $this->json($this->postService->getAllPaginatedPosts($paginationDTO));
    }

    #[Route('/get/{id}', name: 'get_post_by_id', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        return $this->json($this->postService->getPostById($id));
    }

    #[Route('/create', name: 'create_post', methods: ['POST'])]
    public function create(#[MapRequestPayload] PostPayloadDTO $postCreateDTO): JsonResponse
    {
        return $this->json($this->postService->createPost($postCreateDTO), Response::HTTP_CREATED);
    }

    #[Route('/update/{id}', name: 'update_post', methods: ['PUT'])]
    public function update(int $id, #[MapRequestPayload] PostPayloadDTO $postUpdateDTO): JsonResponse
    {
        return $this->json($this->postService->updatePost($id , $postUpdateDTO));
    }

    #[Route('/delete/{id}', name: 'delete_post', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return $this->json($this->postService->deletePost($id), Response::HTTP_NO_CONTENT);
    }
}
