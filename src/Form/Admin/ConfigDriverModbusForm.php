<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Entity\Admin\ConfigDriverModbus;

/**
 * Form class for ModbusTCP driver configuration
 *
 * @author Mateusz Mirosławski
 */
class ConfigDriverModbusForm extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('mode', ChoiceType::class, array('choices'  => array(
                                                        'TCP' => 'TCP',
                                                        'RTU' => 'RTU',
                                                        ),
                                                    'label' => 'Mode'
                                            ))
            ->add('slaveID', null, array('label' => 'Slave ID'))
            ->add('registerCount', null, array('label' => 'Registers to read'))
            ->add('driverPolling', null, array('label' => 'Driver polling interval [ms]'))
            ->add('TCP_addr', null, array('label' => 'Slave IP address'))
            ->add('TCP_port', null, array('label' => 'Port'))
            ->add('RTU_port', null, array('label' => 'COM port name'))
            ->add('RTU_baud', null, array('label' => 'COM baud rate'))
            ->add('RTU_parity', ChoiceType::class, array('choices'  => array(
                                                        'none' => 'N',
                                                        'even' => 'E',
                                                        'odd' => 'O',
                                                        ),
                                                    'label' => 'Parity'
                                            ))
            ->add('RTU_dataBit', ChoiceType::class, array('choices'  => array(
                                                        '5' => 5,
                                                        '6' => 6,
                                                        '7' => 7,
                                                        '8' => 8,
                                                        ),
                                                    'label' => 'Data bit'
                                            ))
            ->add('RTU_stopBit', ChoiceType::class, array('choices'  => array(
                                                        '1' => 1,
                                                        '2' => 2,
                                                        ),
                                                    'label' => 'Stop bit'
                                            ))
            ->add('save', SubmitType::class, array('label' => 'Save'));
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        
        $resolver->setDefaults(array(
            'data_class' => ConfigDriverModbus::class,
        ));
    }
}
