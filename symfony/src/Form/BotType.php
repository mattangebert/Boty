<?php

namespace App\Form;

use App\Entity\Bot;
use App\Entity\Personality;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('enabled', CheckboxType::class, [
                'label' => 'Bot is enabled',
                'required' => false
            ])
            ->add('personality', EntityType::class, [
                'class' => Personality::class,
                'choice_label' => 'name'
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bot::class
        ]);
    }
}