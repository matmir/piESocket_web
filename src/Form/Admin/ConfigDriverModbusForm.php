<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Admin\ConfigDriverModbus;

/**
 * Form class for ModbusTCP driver configuration
 *
 * @author Mateusz Mirosławski
 */
class ConfigDriverModbusForm extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('ipAddress', null, array('label' => 'Slave IP address'))
            ->add('port', null, array('label' => 'Port'))
            ->add('slaveID', null, array('label' => 'Slave ID'))
            ->add('registerCount', null, array('label' => 'Registers to read'))
            ->add('driverPolling', null, array('label' => 'Driver polling interval [ms]'))
            ->add('save', SubmitType::class, array('label' => 'Save'));
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        
        $resolver->setDefaults(array(
            'data_class' => ConfigDriverModbus::class,
        ));
    }
}
