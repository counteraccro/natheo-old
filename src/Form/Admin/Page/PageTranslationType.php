<?php

namespace App\Form\Admin\Page;

use App\Entity\Admin\Page\PageTranslation;
use App\Form\AppType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageTranslationType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pageTitle', TextType::class, [
                'label' => $this->translator->trans('admin_page#Titre de la page'),
                'required' => true,
                'attr' => ['placeholder' => $this->translator->trans('admin_page#Titre de la page')],
                'help' => 'Url : '
            ])
            ->add('slug', HiddenType::class, [
                'label' => $this->translator->trans('admin_page#Slug'),
                'attr' => ['placeholder' => $this->translator->trans('admin_page#Slug')],
                'help' => $this->translator->trans('admin_page#Le slug permet de définir l\'url de la page, par défault c\'est le titre')
            ])
            ->add('language', TextType::class)
            ->add('navigationTitle', TextType::class, [
                'label' => $this->translator->trans('admin_page#Titre de la page dans les menus'),
                'required' => true,
                'attr' => ['placeholder' => $this->translator->trans('admin_page#Titre de la page dans le menus')],
                'help' => $this->translator->trans('admin_page#Le titre de la page qui apparaitra dans les menu')
            ])
            ->add('metaDescription', TextType::class, [
                'label' => $this->translator->trans('admin_page#Meta Description'),
                'required' => false,
                'attr' => ['placeholder' => $this->translator->trans('admin_page#Meta Description')],
                'help' => $this->translator->trans('admin_page#Description qui sera affiché dans les résultats des moteurs de recherches')
            ])
            ->add('metaKeyword', TextType::class, [
                'label' => $this->translator->trans('admin_page#Mots clés'),
                'required' => false,
                'attr' => ['placeholder' => $this->translator->trans('admin_page#Mots clés')],
                'help' => $this->translator->trans('admin_page#Mot clés pour décrire la catégorie pour le SEO')
            ])
            ->add('metaExtraMetaTags', TextareaType::class, [
                'label' => $this->translator->trans('admin_page#Autres méta tags'),
                'required' => false,
                'attr' => ['placeholder' => $this->translator->trans('admin_page#Autres méta tags')],
                'help' => $this->translator->trans('admin_page#Saisissez ici vos métatags personnalisés qui seront affichés dans la balise <head> de la page')
            ])//->add('page')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PageTranslation::class,
        ]);
    }
}
