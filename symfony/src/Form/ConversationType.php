<?php

namespace App\Form;

use App\Entity\Bot;
use App\Entity\Category;
use App\Entity\PersonalityTyp;
use App\Entity\PhraseTyp;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConversationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bot', EntityType::class, [
                'class' => Bot::class,
                'choice_label' => 'name',
                'label' => 'Bot'
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Category'
            ])
            ->add('type', EntityType::class, [
                'class' => PhraseTyp::class,
                'choice_label' => 'name',
                'label' => 'Phrase Typ'
            ])
            ->add('personality', EntityType::class, [
                'class' => PersonalityTyp::class,
                'choice_label' => 'name',
                'label' => 'Your Personality'
            ])
            ->add('talk_self', SubmitType::class, [
                'label' => 'Say Something'
            ])
            ->add('talk_bot', SubmitType::class, [
                'label' => 'Bot say something'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
