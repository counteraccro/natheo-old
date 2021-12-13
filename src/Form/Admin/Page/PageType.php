<?php

namespace App\Form\Admin\Page;

use App\Entity\Admin\Page\Page;
use App\Form\AppType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('can_have_children', CheckboxType::class, [
                'label' => $this->translator->trans('admin_page#Autoriser que cette page puisse avoir des enfants'),
                'help' => $this->translator->trans('admin_page#Si cette case est coché, la page peut être associé à une autre page'),
                'required' => false
            ])
            ->add('can_edit', CheckboxType::class, [
                'label' => $this->translator->trans('admin_page#Autoriser que cette page puisse être modifiée'),
                'help' => $this->translator->trans('admin_page#Si cette case est coché, seul l\'utilisateur root pourra modifier cette page'),
                'required' => false
            ])
            ->add('can_delete', CheckboxType::class, [
                'label' => $this->translator->trans('admin_page#Autoriser que cette page puisse être supprimée'),
                'help' => $this->translator->trans('admin_page#Si cette case est coché, seul l\'utilisateur root pourra supprimer cette page'),
                'required' => false
            ])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    $this->translator->trans('admin_page#Masquer') => 0,
                    $this->translator->trans('admin_page#Publier') => 1,
                ] ,
                'expanded' => true,
                'multiple' => false,
                'label' => $this->translator->trans('admin_page#Publication'),
                'required' => true,
                //'help' => $this->translator->trans('admin_faq#Le titre de la page sur le navigateur (balise <title>)')
            ])
            //->add('create_on')
            //->add('edited_on')
            ->add('base', HiddenType::class)
            //->add('parent')
            //->add('user')
            ->add('pageTranslations', CollectionType::class, [
                'entry_type' => PageTranslationType::class,
                'entry_options' => ['label' => false],
            ])
            ->add("valider", SubmitType::class, [
                'label' => $this->translator->trans('admin_page#Valider')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
