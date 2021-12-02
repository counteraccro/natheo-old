<?php

namespace App\Form\Modules\FAQ;

use App\Entity\Modules\FAQ\FaqCategory;
use App\Entity\Modules\FAQ\FaqQuestionAnswer;
use App\Form\AppType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FaqQuestionAnswerType extends AppType
{
    /**
     * Locale courante
     * @var string
     */
    private string $currentLocal = '';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->currentLocal = $options['current_local'];

        $builder
            ->add('faqCategory', EntityType::class, [
                'class' => FaqCategory::class,
                'choice_label' => function ($faqCategory) {
                    /** @var FaqCategory  $faqCategory */
                    foreach($faqCategory->getFaqCategoryTranslations() as $faqCategoryTranslation)
                    {
                        if($faqCategoryTranslation->getLanguage() == $this->currentLocal)
                        {
                            return $faqCategoryTranslation->getTitle();
                        }
                    }
                    return $faqCategory->getFaqCategoryTranslations()->first()->getTitle();
                },
                'label' => $this->translator->trans('admin_faq#Catégorie'),
            ])
            ->add('position', HiddenType::class)
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    $this->translator->trans('admin_faq#Masquer') => 0,
                    $this->translator->trans('admin_faq#Publier') => 1,
                ] ,
                'empty_data' => 0,
                'expanded' => true,
                'multiple' => false,
                'label' => $this->translator->trans('admin_faq#Publication'),
                'required' => true,
                //'help' => $this->translator->trans('admin_faq#Le titre de la page sur le navigateur (balise <title>)')
            ])
            ->add('faqQuestionAnswerTranslations', CollectionType::class, [
                'entry_type' => FaqQuestionAnswerTranslationType::class,
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
            'data_class' => FaqQuestionAnswer::class,
            'positions' => [],
            'current_action' => '',
            'current_local' => ''
        ]);
    }
}
