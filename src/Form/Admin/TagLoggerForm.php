<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use App\Entity\Admin\TagLoggerEntity;

/**
 * Form class for write Tag logger
 *
 * @author Mateusz Mirosławski
 */
class TagLoggerForm extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('ltid', HiddenType::class)
            ->add('ltTagName', null, array('label' => 'Tag name'))
            ->add('ltInterval', ChoiceType::class, array('choices'  => array(
                                                        '100ms' => 1,
                                                        '200ms' => 2,
                                                        '500ms' => 3,
                                                        '1s' => 4,
                                                        'Xs' => 5,
                                                        'On change' => 6,
                                                        ),
                                                    'label' => 'Log interval'
                                            ))
            ->add('ltIntervalS', null, array('label' => 'Seconds interval'))
            ->add('save', SubmitType::class, array('label' => 'Save'));
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        
        $resolver->setDefaults(array(
            'data_class' => TagLoggerEntity::class,
        ));
    }
}
