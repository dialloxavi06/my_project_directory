<?php

namespace App\Form;

use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\PostSubmitEvent; 

class FormListenerFactory
{

    public function autoSlug(string $field): callable
    {
      return function (PreSubmitEvent $event) use ($field) {
        $entity = $event->getData();
        if (empty($entity[$field])) {
          $slug = strtolower(str_replace(' ', '-', $entity['name']));
          $entity[$field] = $slug;
          $event->setData($entity);
        }
        
      };
     
    }

    public function timestamps() : callable
    {
        return function (PostSubmitEvent  $event) {
            $data = $event->getData();
            $data->setUpdatedAt(new \DateTimeImmutable());
            if (null === $data->getCreatedAt()) {
                $data->setCreatedAt(new \DateTimeImmutable());
            }
        };
    }
        
    
}