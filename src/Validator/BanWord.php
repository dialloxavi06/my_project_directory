<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BanWord extends Constraint
{
    public function __construct(
        $options = null, $groups = null, 
        $payload = null,
        public string $message = 'This containts a banned word "{{ banWord }}".',
        public array $bannedWords = [
        'spam', 
        'money', 
        'free offer', 
        'nigeria', 
        'inheritance', 
        'lot']

    )
    {
        parent::__construct($options, $groups, $payload);
    }
  
    
}
