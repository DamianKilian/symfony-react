<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ProductDto
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public $name;

    #[Assert\NotBlank]
    #[Assert\Type('numeric')]
    public $num;
}
