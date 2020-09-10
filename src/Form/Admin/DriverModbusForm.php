<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use App\Entity\Admin\DriverModbusEntity;
use App\Entity\Admin\DriverModbusMode;

/**
 * Form class for ModbusTCP driver configuration
 *
 * @author Mateusz Mirosławski
 */
class DriverModbusForm extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('connId', HiddenType::class)
            ->add('connName', null, array('label' => 'Connection name'))
            ->add('id', HiddenType::class)
            ->add('mode', ChoiceType::class, array('choices'  => array(
                                                    'TCP' => DriverModbusMode::TCP,
                                                    'RTU' => DriverModbusMode::RTU,
                                                    ),
                                                'label' => 'Mode'
                                            ))
            ->add('registerCount', null, array('label' => 'Registers to read [words]'))
            ->add('driverPolling', null, array('label' => 'Driver polling interval [ms]'))
            ->add('TCP_addr', null, array('label' => 'Slave IP address'))
            ->add('TCP_port', null, array('label' => 'Port'))
            ->add('TCP_use_slaveID', ChoiceType::class, array('choices'  => array(
                                                    'No' => 0,
                                                    'Yes' => 1,
                                                    ),
                                                'label' => 'Use SlaveID'
                                            ))
            ->add('slaveID', null, array('label' => 'Slave ID'))
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
            'data_class' => DriverModbusEntity::class,
        ));
    }
}
