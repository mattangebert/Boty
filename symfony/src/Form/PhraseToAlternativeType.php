<?php

namespace App\Form;

use App\Entity\PhraseToAlternative;
use App\Entity\Phrase;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhraseToAlternativeType extends AbstractType
{
    private $builder;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->builder = $builder;

        $this->builder
            ->add('Phrase', EntityType::class, [
                'class' => Phrase::class,
                'choice_label' => 'phrase',
                'disabled' => true
            ]);


        $this->builder
            ->add('AlternativePhrase', EntityType::class, [
                'class' => Phrase::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->andWhere('p.phraseTyp = :typ')
                        ->setParameter('typ', $this->builder->getData()->getPhrase()->getPhraseTyp())
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
            'data_class' => PhraseToAlternative::class,
        ]);
    }
}
