<?php

namespace App\Form\Modules\Menu;

use App\Entity\Admin\Page\Page;
use App\Entity\Modules\Menu\MenuElement;
use App\Form\AppType;
use App\Service\Admin\PageService;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuElementType extends AppType
{

    private int $menuId = 0;

    private string $current_local;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $this->menuId = $options['menu_id'];
        $this->current_local = $options['current_local'];

        $builder
            ->add('parent', EntityType::class, [
                'label' => $this->translator->trans('admin_menu#Elément de menu parent'),
                'help' => $this->translator->trans('admin_menu#Selectionner le menu élément qui sera parent de celui ci'),
                'query_builder' => function (EntityRepository $er) {

                    return $er->createQueryBuilder('me')
                        ->where('me.menu = :menu_id')
                        ->setParameter('menu_id', $this->menuId)
                        ->orderBy('me.position', 'ASC');
                },
                'label_html' => true,
                'class' => MenuElement::class,
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                'placeholder' => $this->translator->trans('admin_menu#Racine du menu'),
                'choice_label' => function (MenuElement $menuElement) {
                   return $menuElement->getLabel();
                },
            ])
            ->add('position', HiddenType::class)
            ->add('url', TextType::class, [
                'label' => $this->translator->trans('admin_menu#Lien direct'),
                'attr' => ['placeholder' => $this->translator->trans('admin_menu#Lien direct')],
                'help' => $this->translator->trans('admin_menu#Pour fonctionner correctement l\'url doit être complète.'),
                'required' => false,
            ])
            //->add('disabled')
            ->add('isDirectLink', CheckboxType::class, [
                'label' => $this->translator->trans('admin_menu#Définir en lien direct'),
                'required' => false,
                'help' => $this->translator->trans('admin_menu#Dans le cas d\'un lien direct, vous pouvez saisir vous même le lien qu\'il soit extérieur ou intérieur')
            ])
            //->add('menu')
            ->add('page', EntityType::class, [
                'label' => $this->translator->trans('admin_menu#Pages à liées au menu'),
                'help' => $this->translator->trans('admin_menu#Url') . ' : ',
                'query_builder' => function (EntityRepository $er) {

                    return $er->createQueryBuilder('p')
                        ->where('p.status = :status')
                        ->setParameter('status', PageService::PAGE_STATUS_PUBLIER)
                        ->orderBy('p.edited_on', 'ASC');
                },
                'label_html' => true,
                'class' => Page::class,
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                'placeholder' => $this->translator->trans('admin_menu#Aucune page'),
                'choice_label' => function (Page $page) {

                    foreach($page->getPageTranslations() as $pageTranslation)
                    {
                        if($this->current_local == $pageTranslation->getLanguage())
                        {
                            return $pageTranslation->getPageTitle();
                        }
                    }
                    //return $page->getPageTranslations()->first()->
                },
            ])
            ->add('slug', TextType::class, [
                'label' => $this->translator->trans('admin_menu#Slug custom'),
                'attr' => ['placeholder' => $this->translator->trans('admin_menu#Slug custom')],
                'help' => $this->translator->trans('admin_menu#Laisser vide si vous souhaitez utilisé le slug rattaché à l\'élément choisi'),
                'required' => false,
            ])
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
            'menu_id' => 0,
            'current_local' => 'fr'
        ]);
    }
}
