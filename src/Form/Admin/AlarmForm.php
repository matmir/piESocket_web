<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use App\Entity\Admin\AlarmEntity;

/**
 * Form class for write Alarm
 *
 * @author Mateusz Mirosławski
 */
class AlarmForm extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('adid', HiddenType::class)
            ->add('adTagName', null, array('label' => 'Tag name'))
            ->add('adPriority', null, array('label' => 'Priority'))
            ->add('adMessage', null, array('label' => 'Message'))
            ->add('adTrigger', ChoiceType::class, array('choices'  => array(
                                                            'BIN' => 1,
                                                            'Tag>value' => 2,
                                                            'Tag<value' => 3,
                                                            'Tag>=value' => 4,
                                                            'Tag<=value' => 5,
                                                            'Tag=value' => 6,
                                                            'Tag!=value' => 7
                                                        ),
                                                    'label' => 'Trigger mode'
                                            ))
            ->add('adTriggerB', ChoiceType::class, array('choices'  => array(
                                                            'false' => 0,
                                                            'true' => 1
                                                        ),
                                                    'label' => 'Binary value'
                                            ))
            ->add('adTriggerN', null, array('label' => 'Numeric value'))
            ->add('adTriggerR', null, array('label' => 'Real value'))
            ->add('adAutoAck', ChoiceType::class, array('choices'  => array(
                                                            'false' => 0,
                                                            'true' => 1
                                                        ),
                                                    'label' => 'Auto ack'
                                            ))
            ->add('adFeedbackNotACK', null, array('label' => 'Feedback tag (alarm not ack)'))
            ->add('adHWAck', null, array('label' => 'Tag for alarm ack'))
            ->add('save', SubmitType::class, array('label' => 'Save'));
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        
        $resolver->setDefaults(array(
            'data_class' => AlarmEntity::class,
        ));
    }
}
