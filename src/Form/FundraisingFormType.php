<?php

namespace App\Form;

use App\Entity\Fundraising;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FundraisingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control mb-3', 'placeholder' => 'Enter title']
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control mb-3', 'placeholder' => 'Enter the description for your fundraiser']
            ])
            ->add('goal', IntegerType::class, [
                'attr' => ['class' => 'form-control mb-3', 'placeholder' => 'Enter the amount of money you wish to collect']
            ])
            ->add('deadline', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control mb-3 d-flex align-items-center', 'min' => date('Y-m-d')]
            ])
            ->add('image_path', FileType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-control mb-3'],
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fundraising::class,
        ]);
    }
}
