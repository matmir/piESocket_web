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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Email;
use App\Entity\AppException;
use App\Entity\Admin\User;

/**
 * Form class for write User
 *
 * @author Mateusz Mirosławski
 */
class UserForm extends AbstractType implements DataMapperInterface
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', HiddenType::class, array('constraints' => [
                                                new PositiveOrZero()
                                            ]))
            ->add('username', TextType::class, array('label' => 'User name',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Length(['max' => 25]),
                                            ]))
            ->add('oldPassword', PasswordType::class, array('label' => 'Old password',
                                            'constraints' => [
                                                new Length(['max' => 200]),
                                            ]))
            ->add('password1', PasswordType::class, array('label' => 'Password',
                                            'constraints' => [
                                                new Length(['max' => 200]),
                                            ]))
            ->add('password2', PasswordType::class, array('label' => 'Repeat password',
                                            'constraints' => [
                                                new Length(['max' => 200]),
                                            ]))
            ->add('email', EmailType::class, array('label' => 'e-mail',
                                        'constraints' => [
                                            new Email(['message' => "The email '{{ value }}' is not a valid email"]),
                                            new Length(['max' => 254]),
                                        ]))
            ->add('userRole', ChoiceType::class, array('label' => 'Role',
                                            'choices'  => array(
                                                'ADMIN' => 'ROLE_ADMIN',
                                                'USER' => 'ROLE_USER',
                                            ),
                                            'constraints' => [
                                                new NotBlank(),
                                                new Length(['max' => 20]),
                                            ]))
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ->setDataMapper($this);
    }
    
    /**
     * @param User|null $viewData
     */
    public function mapDataToForms($viewData, $forms)
    {
        // there is no data yet, so nothing to prepopulate
        if (null === $viewData) {
            return;
        }

        // invalid data type
        if (!$viewData instanceof User) {
            throw new UnexpectedTypeException($viewData, User::class);
        }

        /** @var FormInterface[] $forms */
        $aforms = iterator_to_array($forms);

        // initialize form field values
        $aforms['id']->setData($viewData->getId());
        $aforms['username']->setData($viewData->getUsername());
        $aforms['email']->setData($viewData->getEmail());
        $aforms['userRole']->setData($viewData->getRoles()[0]);
    }

    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $aforms = iterator_to_array($forms);
        
        try {
            // Check password
            $pass = '';
            $p1 = trim($aforms['password1']->getData());
            $p2 = trim($aforms['password2']->getData());
            
            if ($p1 != '' || $p2 != '') {
                if ($p1 == $p2) {
                    $pass = $p1;
                } else {
                    throw new AppException(
                        "Given passwords are not equal!",
                        AppException::USER_PASSWORD_NOT_EQUAL
                    );
                }
            }

            // Create object
            $viewData = new User(
                $aforms['id']->getData(),
                $aforms['username']->getData(),
                $pass,
                $aforms['email']->getData(),
                $aforms['userRole']->getData()
            );
        } catch (AppException $ex) {
            if ($ex->getCode() == AppException::USER_OLD_PASSWORD_WRONG) {
                $aforms['oldPassword']->addError(new FormError($ex->getMessage()));
            } elseif ($ex->getCode() == AppException::USER_PASSWORD_NOT_EQUAL) {
                $aforms['password1']->addError(new FormError($ex->getMessage()));
            }
        }
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}
