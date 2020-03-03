<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use App\Entity\Admin\UserEntity;

/**
 * Form class for write User
 *
 * @author Mateusz Mirosławski
 */
class UserForm extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('id', HiddenType::class)
            ->add('username', null, array('label' => 'User name'))
            ->add('oldPassword', PasswordType::class, array('label' => 'Old password'))
            ->add('password1', PasswordType::class, array('label' => 'Password'))
            ->add('password2', PasswordType::class, array('label' => 'Repeat password'))
            ->add('email', EmailType::class, array('label' => 'e-mail'))
            ->add('userRole', ChoiceType::class, array('choices'  => array(
                                                        'ADMIN' => 'ROLE_ADMIN',
                                                        'USER' => 'ROLE_USER',
                                                        ),
                                                    'label' => 'Role'
                                            ))
            ->add('save', SubmitType::class, array('label' => 'Save'));
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        
        $resolver->setDefaults(array(
            'data_class' => UserEntity::class,
        ));
    }
}
