<?php

namespace App\DTO\Payload;

use Symfony\Component\Validator\Constraints as Assert;

class PaginationDTO
{
    public function __construct(
        #[Assert\NotBlank(message: 'Page cannot be blank')]
        #[Assert\Length(min: 1, max: 10, maxMessage: 'Page cannot be longer than 10 characters')]
        #[Assert\Positive(message: 'Page must be greater than 0')]
        public int $page,

        #[Assert\NotBlank(message: 'Limit cannot be blank')]
        #[Assert\Length(min: 1, max: 10, maxMessage: 'Limit cannot be longer than 10 characters')]
        #[Assert\Positive(message: 'Limit must be greater than 0')]
        public int $limit,
    )
    {}
}