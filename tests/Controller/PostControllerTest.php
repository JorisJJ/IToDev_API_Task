<?php

namespace App\Tests\Controller;

use App\Controller\PostController;
use App\DataFixtures\PostFixtures;
use App\DTO\Payload\PostPayloadDTO;
use App\Kernel;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class PostControllerTest extends KernelTestCase
{
    private PostController $postController;

    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    protected function setUp(): void
    {
        self::bootKernel();
        $this->postController = static::getContainer()->get(PostController::class);

        // Refresh Database
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $connection = $entityManager->getConnection();

        $connection->executeStatement('TRUNCATE TABLE post;');
        $connection->executeStatement('ALTER TABLE post AUTO_INCREMENT = 1;');
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=1;');

        $purger = new ORMPurger($entityManager);
        $executor = new ORMExecutor($entityManager, $purger);

        $loader = new Loader();
        $loader->addFixture(new PostFixtures());
        $executor->execute($loader->getFixtures());
    }

    public function testCreatePost(): void
    {
        $postPayloadDTO = new PostPayloadDTO('Test Title', 'test CONTENT');

        $response = $this->postController->create($postPayloadDTO);
        $data = json_decode($response->getContent(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(201, $response->getStatusCode());
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('title', $data);
        $this->assertArrayHasKey('content', $data);
        $this->assertArrayHasKey('createdAt', $data);
        $this->assertArrayHasKey('updatedAt', $data);
    }

    public function testDeletePost(): void
    {
        $postId = 5;
        $response = $this->postController->delete($postId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(204, $response->getStatusCode());
    }
}
