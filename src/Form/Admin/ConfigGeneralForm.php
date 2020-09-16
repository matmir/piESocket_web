<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Admin\ConfigGeneral;

/**
 * Form class for system general configuration
 *
 * @author Mateusz Mirosławski
 */
class ConfigGeneralForm extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('alarmingUpdateInterval', null, array('label' => 'Alarm thread update interval [ms]'))
            ->add('processUpdateInterval', null, array('label' => 'Process updater thread update interval [ms]'))
            ->add('tagLoggerUpdateInterval', null, array('label' => 'Tag logger thread update interval [ms]'))
            ->add('scriptSystemUpdateInterval', null, array('label' => 'Script system update interval [ms]'))
            ->add('socketMaxConn', null, array('label' => 'Socket max. connections'))
            ->add('socketPort', null, array('label' => 'Socket port'))
            ->add('serverAppPath', null, array('label' => 'Path to the server application'))
            ->add('webAppPath', null, array('label' => 'Path to the web application'))
            ->add('scriptSystemExecuteScript', null, array('label' => 'System execute script'))
            ->add('userScriptsPath', null, array('label' => 'User script path'))
            ->add('ackAccessRole', ChoiceType::class, array('choices'  => array(
                                                        'ADMIN' => 'ROLE_ADMIN',
                                                        'USER' => 'ROLE_USER',
                                                        'GUEST' => 'ROLE_GUEST',
                                                        ),
                                                    'label' => 'Alarm acknowledgement permission'
                                            ))
            ->add('save', SubmitType::class, array('label' => 'Save'));
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ConfigGeneral::class,
        ));
    }
}
