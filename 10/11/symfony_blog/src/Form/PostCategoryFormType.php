<?php

namespace App\Form;

use App\Entity\PostCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostCategoryFormType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options ): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label'      => 'Назва',
                    'required'   => true,
                    'empty_data' => 'Назва категорії',
                    'trim'       => true,
                ]
            )
            ->add(
                'alias',
                TextType::class,
                [
                    'label'      => 'Аліас',
                    'required'   => true,
                    'empty_data' => 'Аліас заповниться автоматично',
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Надіслати',
                    'attr' =>
                    [
                    ],
                ]
            )
        ;
    }

    public function configureOptions( OptionsResolver $resolver ): void
    {
        $resolver->setDefaults(
            [
                'data_class' => PostCategory::class,
            ]
        );
    }
}
