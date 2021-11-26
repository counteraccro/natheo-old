<?php

namespace App\Form\Modules;

use App\Entity\Modules\Tag;
use App\Form\AppType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => $this->translator->trans('admin_tag#Nom du tag'),
                'attr' => ['placeholder' => $this->translator->trans('admin_tag#Nom du tag')],
                'help' => $this->translator->trans('admin_tag#Le nom du tag doit être unique')
            ])
            ->add('color', ColorType::class, [
                'label' => $this->translator->trans('admin_tag#Couleur du tag'),
            ])
            ->add("valider", SubmitType::class, [
                'label' => $this->translator->trans('admin_tag#Valider')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}
