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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $action = $options['custom_action'];
        $required = true;
        if ($action == 'edit') {
            $required = false;
        }

        $builder
            ->add('email', EmailType::class, [
                'label' => $this->translator->trans('admin_user#Email'),
                'attr' => ['placeholder' => $this->translator->trans('admin_role#Votre email')],
                'help' => $this->translator->trans('admin_user#L\'Email doit être unique'),
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => $this->translator->trans('admin_user#Votre mot de passe n\'est pas identique'),
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => $required,
                'empty_data' => 'aaa',
                'first_options' => ['label' => $this->translator->trans('admin_user#Mot de passe'), 'attr' => ['placeholder' => $this->translator->trans('admin_role#Mot de passe')],],
                'second_options' => ['label' => $this->translator->trans('admin_user#Confirmation du mot de passe'), 'attr' => ['placeholder' => $this->translator->trans('admin_role#Confirmer votre mot de passe')],],
            ])
            ->add('password_strenght', HiddenType::class)
            ->add('name', TextType::class, [
                'label' => $this->translator->trans('admin_user#Votre nom'),
                'attr' => ['placeholder' => $this->translator->trans('admin_role#Votre nom')],
            ])
            ->add('surname', TextType::class, [
                'label' => $this->translator->trans('admin_user#Votre prénom'),
                'attr' => ['placeholder' => $this->translator->trans('admin_role#Votre prénom')],
            ])
            ->add('publicationName', TextType::class, [
                'label' => $this->translator->trans('admin_user#Votre nom de publication'),
                'help' => $this->translator->trans('admin_user#Le nom qui apparaitra sous vos publications'),
                'attr' => ['placeholder' => $this->translator->trans('admin_role#Votre nom de publication')],
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
                'help' => $this->translator->trans('admin_user#Si l\'utilisateur est désactivé, celui ci ne pourra plus ce connecter sur l\'administration'),
            ])
            ->add("valider", SubmitType::class, [
                'label' => $this->translator->trans('admin_user#Valider')
            ])//->add('rolesCms')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'custom_action' => ''
        ]);
    }
}
