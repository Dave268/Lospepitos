<?php

namespace App\Form;

use App\Entity\BestImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use FM\ElfinderBundle\Form\Type\ElFinderType;

class BestImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('url', 		ElFinderType::class, ['instance' => 'form', 'enable' => true, 'attr' => ['class' => 'form-control']])
            ->add('url',                TextType::class)
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
            'data_class' => BestImage::class,
        ]);
    }
}
