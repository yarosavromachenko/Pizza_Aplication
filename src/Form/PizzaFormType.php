<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Pizza;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PizzaFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name')
            ->add('price', NumberType::class, [
                'scale' => 2,
                'disabled' => true
            ])
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'expanded' => true,
                'multiple' => true,
                'choice_attr' => function($choice, $key, $value) {
                    // adds a class like attending_yes, attendingno, etc
                    return ['data-price' => $choice->getCostPrice()];
                },

            ]);

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($builder) {
                /** @var Pizza $data */
                $data = $builder->getData();

                $data->calculatePrice();


            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ($builder) {
                /** @var Pizza $data */
                $data = $builder->getData();

                $data->calculatePrice();
                $form = $event->getForm();
                $form->remove('price')
                    ->add('price');


            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($builder) {
                /** @var Pizza $data */
                $data = $builder->getData();
                $data->calculatePrice();
            }
        );

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pizza::class,
        ]);
    }
}
