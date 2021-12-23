<?php

namespace App\Form\Modules\Menu;

use App\Entity\Modules\Menu\Menu;
use App\Form\AppType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => $this->translator->trans('admin_menu#Titre du menu'),
                'help' => $this->translator->trans('admin_menu#Permet de retrouver facilement le menu dans le choix de menu')
            ])
            ->add('description', TextType::class, [
                'label' => $this->translator->trans('admin_menu#Description du menu'),
                'required' => false,
                'help' => $this->translator->trans('admin_menu#Permet de définir un descriptif du menu pour l\'administration')
            ])
            ->add('disabled', CheckboxType::class, [
                'label' => $this->translator->trans('admin_menu#Désactiver le menu'),
                'required' => false,
                'help' => $this->translator->trans('admin_menu#Un menu désactivé n\'apparaitra plus dans le choix de menu ni sur le front')
            ])
            ->add("valider", SubmitType::class, [
                'label' => $this->translator->trans('admin_menu#Valider')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
