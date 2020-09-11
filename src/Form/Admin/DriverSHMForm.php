<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use App\Entity\Admin\DriverSHMEntity;

/**
 * Form class for SHM driver configuration
 *
 * @author Mateusz Mirosławski
 */
class DriverSHMForm extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('connId', HiddenType::class)
            ->add('connName', null, array('label' => 'Connection name'))
            ->add('id', HiddenType::class)
            ->add('segmentName', null, array('label' => 'Shared memory segment name'))
            ->add('save', SubmitType::class, array('label' => 'Save'));
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        
        $resolver->setDefaults(array(
            'data_class' => DriverSHMEntity::class,
        ));
    }
}
