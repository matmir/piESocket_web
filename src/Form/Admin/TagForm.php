<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use App\Entity\Admin\TagEntity;

/**
 * Form class for write Tag
 *
 * @author Mateusz Mirosławski
 */
class TagForm extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('tid', HiddenType::class)
            ->add('tConnId', ChoiceType::class, array('choices'  => $options['connections'],
                                                    'label' => 'Connection'
                                            ))
            ->add('tName', null, array('label' => 'Name'))
            ->add('tType', ChoiceType::class, array('choices'  => array(
                                                        'Bit' => 1,
                                                        'Byte' => 2,
                                                        'Word' => 3,
                                                        'DWord' => 4,
                                                        'INT' => 5,
                                                        'REAL' => 6,
                                                        ),
                                                    'label' => 'Tag type'
                                            ))
            ->add('tArea', ChoiceType::class, array('choices'  => array(
                                                        'Input' => 1,
                                                        'Output' => 2,
                                                        'Memory' => 3,
                                                        ),
                                                    'label' => 'Area'
                                            ))
            ->add('tByteAddress', null, array('label' => 'Byte address'))
            ->add('tBitAddress', ChoiceType::class, array('choices'  => array(
                                                            '0' => 0,
                                                            '1' => 1,
                                                            '2' => 2,
                                                            '3' => 3,
                                                            '4' => 4,
                                                            '5' => 5,
                                                            '6' => 6,
                                                            '7' => 7,
                                                            ),
                                                        'label' => 'Bit address'
                                            ))
            ->add('tReadAccess', ChoiceType::class, array('choices'  => array(
                                                        'ADMIN' => 'ROLE_ADMIN',
                                                        'USER' => 'ROLE_USER',
                                                        'GUEST' => 'ROLE_GUEST',
                                                        ),
                                                    'label' => 'Read access'
                                            ))
            ->add('tWriteAccess', ChoiceType::class, array('choices'  => array(
                                                        'ADMIN' => 'ROLE_ADMIN',
                                                        'USER' => 'ROLE_USER',
                                                        'GUEST' => 'ROLE_GUEST',
                                                        ),
                                                    'label' => 'Write access'
                                            ))
            ->add('save', SubmitType::class, array('label' => 'Save'));
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        
        $resolver->setDefaults(array(
            'data_class' => TagEntity::class,
            'connections' => array(),
        ));
    }
}
