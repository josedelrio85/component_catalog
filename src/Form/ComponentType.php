<?php

namespace App\Form\Type;

use App\Entity\Component;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComponentType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('path', TextType::class)
      ->add('html_template', TextType::class)
      ->add('html_data', TextareaType::class)
      ->add('html_content', TextareaType::class)
      ->add('styles_template', TextType::class)
      ->add('styles_content', TextareaType::class)
      ->add('save', SubmitType::class)
    ;

  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      // Configure your form options here
      'data_class' => Component::class,
    ]);
  }
}
