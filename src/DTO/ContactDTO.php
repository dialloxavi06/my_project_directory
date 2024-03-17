<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;


class ContactDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 100)]
    public $name = '';
    #[Assert\NotBlank]
    #[Assert\Email]
    public $email = '';
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 1000)]
    public $message = '';
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 40)]
    public string $subject = '';
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 100)]
    public string $service = '';

   
}