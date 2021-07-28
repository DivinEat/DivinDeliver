<?php

namespace App\Form;

use App\Entity\User;
use App\Service\User\UserService;
use App\Validator\UniqueEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Email;

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
                'label' => 'user.firstname'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'user.lastname'
            ])
            ->add('email', EmailType::class, [
                'label' => 'user.email',
                'constraints' => [
                    new UniqueEmail(),
                    new Email()
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'multiple' => false,
                'choices'  => $rolesList
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