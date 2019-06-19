<?php

namespace App\Form;

use App\Entity\Bot;
use App\Entity\Category;
use App\Entity\PersonalityTyp;
use App\Entity\PhraseTyp;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConversationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isResponse', HiddenType::class)
            ->add('personality', EntityType::class, [
                'class' => PersonalityTyp::class,
                'choice_label' => 'name',
                'label' => 'Your Personality'
            ])
        ;

        $builder->addEventListener(
          FormEvents::PRE_SET_DATA,
          function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if(!$data['isResponse']) {
               $form
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
                   ->add('talk_self', SubmitType::class, [
                       'label' => 'Say Something'
                   ])
                   ->add('talk_bot', SubmitType::class, [
                       'label' => 'Bot say something'
                   ])
               ;
            }

            if ($data['isResponse']) {
                $form->add('respond', SubmitType::class, [
                    'label' => 'respond'
                ]);
            }
          }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
