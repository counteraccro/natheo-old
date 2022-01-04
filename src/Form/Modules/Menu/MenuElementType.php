<?php

namespace App\Form\Modules\Menu;

use App\Entity\Modules\Menu\MenuElement;
use App\Form\AppType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuElementType extends AppType
{

    private int $menuId = 0;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $optionParent = [
            'choices' => $options['parent'],
            'label' => $this->translator->trans('admin_menu#Elément de menu parent'),
            'help' => $this->translator->trans('admin_menu#Selectionner le menu élément qui sera parent de celui ci'),
            'placeholder' => $this->translator->trans('admin_menu#Racine du menu'),
        ];

        $builder
            ->add('parent', ChoiceType::class, $optionParent)
            ->add('url', TextType::class, [
                'label' => $this->translator->trans('admin_menu#Lien direct'),
                'attr' => ['placeholder' => $this->translator->trans('admin_menu#Lien direct')],
                'required' => false,
            ])
            //->add('slug')
            //->add('disabled')
            ->add('isDirectLink', CheckboxType::class, [
                'label' => $this->translator->trans('admin_menu#Définir en lien direct'),
                'required' => false,
                'help' => $this->translator->trans('admin_menu#Dans le cas d\'un lien direct, vous pouvez saisir vous même le lien qu\'il soit extérieur ou intérieur')
            ])
            //->add('menu')
            //->add('page')
            ->add("valider", SubmitType::class, [
                'label' => $this->translator->trans('admin_menu#Valider')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MenuElement::class,
            'parent' => [],
            'allow_extra_fields' => true,
        ]);
    }
}
