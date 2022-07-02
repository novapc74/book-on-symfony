<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditCategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Main category",
                'label_attr' => [
                    'class' => 'pt-3'
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Main category'
                ]
            ])
            ->add('subname', TextType::class, [
                'label' => 'Subname',
                'label_attr' => [
                    'class' => 'pt-3'
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Subname',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
