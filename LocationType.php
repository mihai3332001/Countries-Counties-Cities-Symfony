<?php
// src/Form/LocationType.php
namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\CountryTypeSelect;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use GeoNames\Client as GeoNamesClient;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use App\Entity\Country;
use App\Entity\County;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\IssueToStringTransformer;
use  App\Form\DataTransformer\IssueToIntTransformer;
use App\Form\EventListener\DynamicFieldsSubscriber;

class LocationType extends AbstractType
{
    private $transformer;

    public function __construct(IssueToStringTransformer $transformer)
    {
        $this->transformer = $transformer;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add('country', CountryType::class, [
            'label' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'not null',
                ]),
            ],
            'attr' => [
                'class' => 'form-control'
            ],
            //'empty_data' => ''
            //'mapped' => false,
        ])
        ->add('county', EntityType::class, [
            'class' => County::class,
            'label' => false,
            'placeholder' => 'Choose an option', 
            'constraints' => [
                new NotBlank([
                    'message' => 'not null',
                ]),
            ],
            'mapped'  => false,
        ])
        ->add('city', ChoiceType::class, [
            'label' => false,
            'attr'  => [ 'class' => 'form-control'],          
            'placeholder' => 'Choose an option', 
            //'choices' => ['Tirana' => 'Tirana'],
            'constraints' => [
                new NotBlank([
                    'message' => 'not null',
                ]),
            ],
            'mapped'  => false,
        ]);

        $builder->get('city')->resetViewTransformers();

        $builder->get('country')->addModelTransformer($this->transformer);  

        $builder->addEventSubscriber(new DynamicFieldsSubscriber());  

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}