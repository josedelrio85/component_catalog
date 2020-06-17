<?php

namespace App\Form;

use App\Entity\Comp;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

class ComponentType extends AbstractType
{
  private $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $categories = $this->entityManager->getRepository(Category::class)->findAll();

    $builder
      ->add('name', TextType::class)
      ->add('idCategory', ChoiceType::class, [
        'choices' => $categories,
        'choice_value' => 'id',
        'choice_label' => function(?Category $cat) {
          return $cat ? $cat->getName() : '';
        },
        'label' => 'Category',
      ])
      ->add('html_data', TextareaType::class, [
        'label' => 'Html data [JSON]'
      ])
      ->add('html_content', TextareaType::class)
      ->add('styles_content', TextareaType::class)
      ->add('save', SubmitType::class)
    ;

    $builder->get('html_data')
      ->addModelTransformer(new CallbackTransformer(
        function ($input) {
          return json_encode($input, JSON_PRETTY_PRINT);
        },
        function ($input) {
          return $input;
        }
      ));

  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      // Configure your form options here
      'data_class' => Comp::class,
    ]);
  }
}
