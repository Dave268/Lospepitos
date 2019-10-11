<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\ArticleCategory;
use App\Entity\Continent;
use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType ;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use FM\ElfinderBundle\Form\Type\ElFinderType;




class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 		        TextType::class)
            ->add('categories',         EntityType::class, ['class' => ArticleCategory::class,'choice_label' => 'name', 'multiple' => true])
            ->add('date_post', 	        DateType::class, ['widget' => 'single_text', 'attr' => ['type' => 'date']])
            ->add('url',                ElFinderType::class, ['instance' => 'form', 'enable' => true, 'attr' => ['class' => 'form-control']])
            ->add('Country',            EntityType::class, ['class' => Country::class, 'choice_label' => 'name',])
            ->add('gps', 		        TextType::class, ['attr' => ['data-toggle' => 'modal', 'data-target'=> '#mapmodal']])
            ->add('short',		        TextareaType::class)
            ->add('text', 		        TextareaType::class)
			->add('Envoyer', 	        SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
