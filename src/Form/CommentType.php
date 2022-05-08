<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'label'=>"Ajouter un titre",
                'required' => false,
                'attr'=> [
                    'placeholder'=>'Le plus important à savoir'
                ]
            ])
            ->add('rate',IntegerType::class,[
                'label'=>" ",
                'attr'=> [
                    'value'=>0,
                    'class'=>'d-none',
                ]
            ])
            ->add('content', TextareaType::class,[
                'label'=>"Ajouter un commentaire",
                'required' => false,
                'attr'=> [
                    'placeholder'=>"Dites-en plus, qu'est-ce que vous avez aimé ou n'avez pas aimé ?"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
