<?php

namespace App\Form;

use App\Entity\Book;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditBookFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'label_attr' => [
                    'class' => 'pt-3'
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control text-primary',
                    'placeholder' => 'Title',
                    'autofocus' => 'autofocus'
                ]
            ])
            ->add('isbn', TextType::class, [
                'label' => 'isbn',
                'label_attr' => [
                    'class' => 'pt-3'
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'isbn',
                ]
            ])
            ->add('pageCount', IntegerType::class, [
                'label' => 'page count',
                'label_attr' => [
                    'class' => 'pt-3'
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'page count',
                ],
            ])
            ->add('publishedDate', DateType::class, [
                'label' => 'Published date',
                'label_attr' => [
                    'class' => 'pt-3'
                ],
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control w-50 text-center',
                ],
            ])
            ->add('thumbnailUrl', UrlType::class, [
                'label' => 'URL',
                'label_attr' => [
                    'class' => 'pt-3',
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'URL'
                ]
            ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'Short description',
                'label_attr' => [
                    'class' => 'pt-3'
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter short description'
                ],
            ])
            ->add('longDescription', TextareaType::class, [
                'label' => 'Long description',
                'label_attr' => [
                    'class' => 'pt-3',
                    'placeholder' => 'Enter long description'
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('status', TextType::class, [
                'label' => 'Status',
                'label_attr' => [
                    'class' => 'pt-3',
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Status'
                ]
            ])
            // ->add('image', TextType::class, [
            //     'label' => 'image',
            //     'label_attr' => [
            //         'class' => 'pt-3'
            //     ],
            //     'required' => false,
            //     'attr' => [
            //         'class' => 'form-control'
            //     ]
            // ])
            ->add('category')
            ->add('authors', CollectionType::class, [
                'label' => 'Authors',
                'label_attr' => [
                    'class' => 'pt-3'
                ],
                'entry_type' => EditAuthorFormType::class,
                'entry_options' => ['label' => false],
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
