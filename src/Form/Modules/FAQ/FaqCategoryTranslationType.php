<?php

namespace App\Form\Modules\FAQ;

use App\Entity\Modules\FAQ\FaqCategory;
use App\Entity\Modules\FAQ\FaqCategoryTranslation;
use App\Form\AppType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FaqCategoryTranslationType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => $this->translator->trans('admin_faq#Titre de la catégorie'),
                'attr' => ['placeholder' => $this->translator->trans('admin_faq#Titre de la catégorie')],
                'help' => $this->translator->trans('admin_faq#Url') . ' : '
            ])
            ->add('slug', HiddenType::class, [
                'label' => $this->translator->trans('admin_faq#Slug'),
                'attr' => ['placeholder' => $this->translator->trans('admin_faq#Slug')],
                'help' => $this->translator->trans('admin_faq#Le slug permet de définir l\'url de la catégorie, par défault c\'est le titre')
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => $this->translator->trans('admin_faq#Description'),
                'attr' => ['placeholder' => $this->translator->trans('admin_faq#Description'), 'class' => 'input-editor', 'rows' => 15],
                'help' => $this->translator->trans('admin_faq#Ce texte sera présent sous le titre de la catégorie')
            ])
            ->add('language', HiddenType::class, [
                //'help' => $this->translator->trans('admin_tag#Le nom du tag doit être unique')
            ])
            ->add('page_title', TextType::class, [
                'label' => $this->translator->trans('admin_faq#Titre de la page'),
                'required' => false,
                'attr' => ['placeholder' => $this->translator->trans('admin_faq#Titre de la page')],
                'help' => $this->translator->trans('admin_faq#Le titre de la page sur le navigateur (balise <title>)')
            ])
            ->add('meta_description', TextType::class, [
                'label' => $this->translator->trans('admin_faq#Meta Description'),
                'required' => false,
                'attr' => ['placeholder' => $this->translator->trans('admin_faq#Meta Description')],
                'help' => $this->translator->trans('admin_faq#Description qui sera affiché dans les résultats des moteurs de recherches')
            ])
            ->add('meta_keyword', TextType::class, [
                'label' => $this->translator->trans('admin_faq#Mots clés'),
                'required' => false,
                'attr' => ['placeholder' => $this->translator->trans('admin_faq#Mots clés')],
                'help' => $this->translator->trans('admin_faq#Mot clés pour décrire la catégorie pour le SEO')
            ])
            ->add('meta_extra_metatags', TextareaType::class, [
                'label' => $this->translator->trans('admin_faq#Autres méta tags'),
                'required' => false,
                'attr' => ['placeholder' => $this->translator->trans('admin_faq#Autres méta tags')],
                'help' => $this->translator->trans('admin_faq#Saisissez ici vos métatags personnalisés qui seront affichés dans la balise <head> de la page')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FaqCategoryTranslation::class,
        ]);
    }
}
