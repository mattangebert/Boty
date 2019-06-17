<?php

namespace App\Form;

use App\Entity\Phrase;
use App\Entity\PhraseToReply;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhraseToReplyType extends AbstractType
{
    private $builder;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phrase')
            ->add('replyPhrase')
            ->add('save', SubmitType::class)
        ;


        $this->builder = $builder;

        $this->builder
            ->add('phrase', EntityType::class, [
                'class' => Phrase::class,
                'choice_label' => 'phrase',
                'disabled' => true
            ]);


        $this->builder
            ->add('replyPhrase', EntityType::class, [
                'class' => Phrase::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')

                        ->andWhere('p.id != :current')
                        ->setParameter('current', $this->builder->getData()->getPhrase()->getId());
                },
                'choice_label' => 'phrase'
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PhraseToReply::class,
        ]);
    }
}
