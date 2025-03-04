<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class BaseService
{
    protected EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }
}