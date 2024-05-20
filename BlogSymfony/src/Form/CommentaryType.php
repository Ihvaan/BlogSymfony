<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Commentary;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Description')
            ->add('Author')
            ->add('Title')
            ->add('Date', null, [
                'widget' => 'single_text',
            ])
            ->add('article', EntityType::class, [
                'class' => Article::class,
                'choice_label' => 'Title',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentary::class,
        ]);
    }
}
