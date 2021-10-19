<?php

namespace App\Form\Admin;

use App\Entity\User;
use App\Form\AppType;
use App\Twig\Admin\System\DateTwig;
use PharIo\Manifest\Email;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => $this->translator->trans('admin_user#Email'),
                'help' => $this->translator->trans('admin_user#L\'Email doit être unique'),
            ])
            ->add('password_1', PasswordType::class, [
                'label' => $this->translator->trans('admin_user#Mot de passe'),
                'mapped' => false,
            ])
            ->add('password_2', PasswordType::class, [
                'label' => $this->translator->trans('admin_user#Confirmation du mot de passe'),
                'mapped' => false,
            ])
            ->add('name', TextType::class, [
                'label' => $this->translator->trans('admin_user#Votre nom'),
            ])
            ->add('surname', TextType::class, [
                'label' => $this->translator->trans('admin_user#Votre prénom'),
            ])
            ->add('publicationName', TextType::class, [
                'label' => $this->translator->trans('admin_user#Votre nom de publication'),
            ])
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
            ->add('isDisabled', CheckboxType::class, [
                'label' => $this->translator->trans('admin_user#Désactiver cet utilisateur'),
                'required' => false,
            ])
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
