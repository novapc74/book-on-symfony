<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class EditMessageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'pt-3',
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Email',
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Name',
                'label_attr' => [
                    'class' => 'pt-3',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Your name',
                ],
            ])
            ->add('message', TextType::class, [
                'label' => 'Message',
                'label_attr' => [
                    'class' => 'pt-3',
                ],
                'required' => true,
                "attr" => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter your message',
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Phone',
                'label_attr' => [
                    'class' => 'pt-3',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'phone format: +8(XXX) XXX-XX-XX',
                ],
            ])
            ->add('delivered', IntegerType::class, [
                'label' => "Delivered",
                'label_attr' => [
                    'class' => 'form-check-label pt-3',
                    'style'=>'display:none;'
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-check',
                    'style'=>'display:none;'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
