<?php

namespace App\Form;

use App\Entity\Album;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FM\ElfinderBundle\Form\Type\ElFinderType;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',          TextType::class)
            //->add('urlImg', 		ElFinderType::class, ['instance' => 'form', 'enable' => true, 'attr' => ['class' => 'form-control']])
            ->add('urlImg',                TextType::class)
            ->add('browse', ButtonType::class, [
                'label'=> 'browse',
                'attr' => ['class' => 'btn btn-primary btn-sm', 'data-toggle' => 'modal', 'data-target'=>'#flmngrmodal'],
            ])
            ->add('Envoyer', 	SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
