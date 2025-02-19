<?php

namespace App\DTO\Payload;

use Symfony\Component\Validator\Constraints as Assert;

class PostPayloadDTO
{
    public function __construct(
        #[Assert\NotNull(message: 'Title cannot be null')]
        #[Assert\Type('string', message: 'Title must be a string')]
        #[Assert\Length(min: 1, max: 255, minMessage: 'Title has to be at least 1 character', maxMessage: 'Title cannot be longer than 255 characters')]
        public string $title,

        #[Assert\NotNull(message: 'Content cannot be null')]
        #[Assert\Type('string', message: 'Content must be of type text')]
        public string $content,
    )
    {}
}