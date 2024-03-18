<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvents;
use App\Form\FormListenerFactory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CategoryType extends AbstractType
{
    public function __construct(private FormListenerFactory $factory)
    {
        
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'empty_data'=> '', 
            'label' => 'Nom de la catégorie'
        ])
        ->add('slug', TextType::class, [
            'empty_data'=> '', 
            'required' => false,
            'label' => 'Slug'
        ])
       /* ->add('recipes', EntityType::class, [
            'class' => Recipe::class,
            'choice_label' => 'title',
            'multiple' => true,
            'placeholder' => 'Choisir une catégorie',
            'by_reference' => false
            ])
        */
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
            ->addEventListener(FormEvents:: PRE_SUBMIT, $this->factory->autoSlug('name') )
            ->addEventListener(FormEvents:: POST_SUBMIT, $this->factory->timestamps() )

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
