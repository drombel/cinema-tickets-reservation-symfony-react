<?php

namespace App\Form;

use App\Entity\Seat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('col', IntegerType::class)
            ->add('row', IntegerType::class)
            ->add('status', ChoiceType::class, [
                'choices' => [
                    Seat::STATUS_ACTIVE => Seat::STATUS_ACTIVE,
                    Seat::STATUS_HIDDEN => Seat::STATUS_HIDDEN,
                    Seat::STATUS_DISABLED => Seat::STATUS_DISABLED,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Seat::class,
        ]);
    }
}
