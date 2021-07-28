<?php

namespace App\Form;

use App\Entity\User;
use App\Validator\UniqueEmail;
use App\Service\User\UserService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotNull;

class UserType extends AbstractType
{
    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $rolesList = $this->userService->getRolesList();

        $builder
            ->add('firstname', TextType::class, [
                'label' => 'user.firstname',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'user.lastname',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'user.email',
                'constraints' => [
                    new UniqueEmail(),
                    new Email(),
                    new NotBlank()
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'multiple' => false,
                'choices'  => $rolesList,
                'constraints' => [
                    new Choice([
                        'choices' =>  array(null)
                    ])
                ]
            ])
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                     return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                     return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'roles_list' => null,
        ]);
    }
}