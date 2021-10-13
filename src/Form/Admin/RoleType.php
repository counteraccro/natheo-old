<?php

namespace App\Form\Admin;

use App\Entity\Admin\Role;
use App\Form\AppType;
use SebastianBergmann\CodeCoverage\Report\Text;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class RoleType extends AppType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => $this->translator->trans('admin_role#Nom du rôle'),
                'attr' => ['placeholder' => $this->translator->trans('admin_role#Nom du rôle')],
                'help' => $this->translator->trans('admin_role#Le nom du rôle doit être unique')
            ])
            ->add('shortLabel', TextType::class, [
                'label' => $this->translator->trans('admin_role#Nom court du rôle'),
                'attr' => ['placeholder' => $this->translator->trans('admin_role#Nom court du rôle')],
                'help' => $this->translator->trans('admin_role#Permet de facilité l\'identification du rôle')
            ])
            ->add('label', TextType::class, [
                'label' => $this->translator->trans('admin_role#Description du rôle'),
                'attr' => ['placeholder' => $this->translator->trans('admin_role#Description du rôle')],
                'help' => $this->translator->trans('admin_role#Information sur le rôle')
            ])
            ->add('color', ColorType::class, [
                'label' => $this->translator->trans('admin_role#Couleur du rôle'),
            ])
            ->add('canUpdate', CheckboxType::class, [
                'label' => $this->translator->trans('admin_role#Autoriser la mise à jour des droits lors de la mise à jour de l\'application'),
                'required' => false
            ])
            ->add("valider", SubmitType::class, [
                'label' => $this->translator->trans('admin_role#Valider')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
            'allow_extra_fields' => true
        ]);
    }
}
