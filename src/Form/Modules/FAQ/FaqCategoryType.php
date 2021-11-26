<?php

namespace App\Form\Modules\FAQ;

use App\Entity\Modules\FAQ\FaqCategory;
use App\Form\AppType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FaqCategoryType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $optionChoiceType = [
            'choices' => $options['positions'],
            'label' => $this->translator->trans('admin_faq#Position de la catégorie'),
            'help' => $this->translator->trans('admin_faq#La catégorie sera placée à la position choisie, les autres catégories seront décalé de 1 vers le bas ')
        ];

        if($options['current_action'] == 'add')
        {
            $optionChoiceType['data'] = count($options['positions']);
        }

        $builder
            ->add('position', ChoiceType::class, $optionChoiceType)
            //->add('create_on')
            ->add('faqCategoryTranslations', CollectionType::class, [
                'entry_type' => FaqCategoryTranslationType::class,
                'entry_options' => ['label' => false],
            ])
            ->add("valider", SubmitType::class, [
                'label' => $this->translator->trans('admin_faq#Valider')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FaqCategory::class,
            'positions' => [],
            'current_action' => '',
        ]);
    }
}
