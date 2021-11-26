<?php

namespace App\Form\Modules\FAQ;

use App\Entity\Modules\FAQ\FaqCategory;
use App\Form\AppType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FaqCategoryType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('position')
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
        ]);
    }
}
