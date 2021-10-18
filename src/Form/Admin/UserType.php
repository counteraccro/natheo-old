<?php

namespace App\Form\Admin;

use App\Entity\User;
use App\Form\AppType;
use App\Twig\Admin\System\DateTwig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('password')
            ->add('name')
            ->add('surname')
            ->add('publicationName')
            ->add('avatar', FileType::class, [
                'label' => $this->translator->trans('admin_user#Votre avatar'),
                'help' => $this->translator->trans('admin_user#Extension autorisées : GIF, PNG, JPEG - taille maximal 1024 ko'),
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif', 'image/png', 'image/jpeg'
                        ],
                        'mimeTypesMessage' => $this->translator->trans('admin_user#Extension autorisées : GIF, PNG, JPEG'),
                    ])
                ],
            ])
            ->add('lastLogin')
            ->add('lastPasswordUpdae')
            ->add('password_strenght')
            ->add('isDisabled')
            ->add("valider", SubmitType::class, [
                'label' => $this->translator->trans('admin_user#Valider')
            ])
            //->add('rolesCms')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
