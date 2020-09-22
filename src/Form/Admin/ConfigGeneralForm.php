<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use App\Entity\Admin\ConfigGeneral;

/**
 * Form class for system general configuration
 *
 * @author Mateusz Mirosławski
 */
class ConfigGeneralForm extends AbstractType implements DataMapperInterface
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('alarmingUpdateInterval', IntegerType::class, array(
                                            'label' => 'Alarm thread update interval [ms]',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => ConfigGeneral::UPDATE_INTERVAL_MIN,
                                                            'max' => ConfigGeneral::UPDATE_INTERVAL_MAX]),
                                            ],
                                            'empty_data' => '0'))
            ->add('processUpdateInterval', IntegerType::class, array(
                                            'label' => 'Process updater thread update interval [ms]',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => ConfigGeneral::UPDATE_INTERVAL_MIN,
                                                            'max' => ConfigGeneral::UPDATE_INTERVAL_MAX]),
                                            ],
                                            'empty_data' => '0'))
            ->add('tagLoggerUpdateInterval', IntegerType::class, array(
                                            'label' => 'Tag logger thread update interval [ms]',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => ConfigGeneral::UPDATE_INTERVAL_MIN,
                                                            'max' => ConfigGeneral::UPDATE_INTERVAL_MAX]),
                                            ],
                                            'empty_data' => '0'))
            ->add('scriptSystemUpdateInterval', IntegerType::class, array(
                                            'label' => 'Script system update interval [ms]',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => ConfigGeneral::UPDATE_INTERVAL_MIN,
                                                            'max' => ConfigGeneral::UPDATE_INTERVAL_MAX]),
                                            ],
                                            'empty_data' => '0'))
            ->add('socketMaxConn', IntegerType::class, array('label' => 'Socket max. connections',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => 1,
                                                            'max' => 100]),
                                            ],
                                            'empty_data' => '0'))
            ->add('socketPort', IntegerType::class, array('label' => 'Socket port',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => 1,
                                                            'max' => 65535]),
                                            ],
                                            'empty_data' => '0'))
            ->add('serverAppPath', TextType::class, array('label' => 'Path to the server application',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Length(['max' => 200]),
                                            ],
                                            'empty_data' => ''))
            ->add('webAppPath', TextType::class, array('label' => 'Path to the web application',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Length(['max' => 200]),
                                            ],
                                            'empty_data' => ''))
            ->add('scriptSystemExecuteScript', TextType::class, array('label' => 'System execute script',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Length(['max' => 200]),
                                            ],
                                            'empty_data' => ''))
            ->add('userScriptsPath', TextType::class, array('label' => 'User script path',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Length(['max' => 200]),
                                            ],
                                            'empty_data' => ''))
            ->add('ackAccessRole', ChoiceType::class, array('label' => 'Alarm acknowledgement permission',
                                            'choices'  => array(
                                                'ADMIN' => 'ROLE_ADMIN',
                                                'USER' => 'ROLE_USER',
                                                'GUEST' => 'ROLE_GUEST',
                                            ),
                                            'constraints' => [
                                                new NotBlank(),
                                                new Length(['max' => 20]),
                                            ],
                                            'empty_data' => 'ROLE_ADMIN'
                                            ))
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ->setDataMapper($this);
    }
    
    /**
     * @param ConfigGeneral|null $viewData
     */
    public function mapDataToForms($viewData, $forms)
    {
        // there is no data yet, so nothing to prepopulate
        if (null === $viewData) {
            return;
        }

        // invalid data type
        if (!$viewData instanceof ConfigGeneral) {
            throw new UnexpectedTypeException($viewData, ConfigGeneral::class);
        }

        /** @var FormInterface[] $forms */
        $aforms = iterator_to_array($forms);

        // initialize form field values
        $aforms['alarmingUpdateInterval']->setData($viewData->getAlarmingUpdateInterval());
        $aforms['processUpdateInterval']->setData($viewData->getProcessUpdateInterval());
        $aforms['tagLoggerUpdateInterval']->setData($viewData->getTagLoggerUpdateInterval());
        $aforms['scriptSystemUpdateInterval']->setData($viewData->getScriptSystemUpdateInterval());
        $aforms['socketMaxConn']->setData($viewData->getSocketMaxConn());
        $aforms['socketPort']->setData($viewData->getSocketPort());
        $aforms['serverAppPath']->setData($viewData->getServerAppPath());
        $aforms['webAppPath']->setData($viewData->getWebAppPath());
        $aforms['scriptSystemExecuteScript']->setData($viewData->getScriptSystemExecuteScript());
        $aforms['userScriptsPath']->setData($viewData->getUserScriptsPath());
        $aforms['ackAccessRole']->setData($viewData->getAckAccessRole());
    }

    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $aforms = iterator_to_array($forms);
        
        // Create object
        $viewData = new ConfigGeneral(
            $aforms['alarmingUpdateInterval']->getData(),
            $aforms['processUpdateInterval']->getData(),
            $aforms['tagLoggerUpdateInterval']->getData(),
            $aforms['scriptSystemUpdateInterval']->getData(),
            $aforms['socketMaxConn']->getData(),
            $aforms['socketPort']->getData(),
            $aforms['serverAppPath']->getData(),
            $aforms['webAppPath']->getData(),
            $aforms['scriptSystemExecuteScript']->getData(),
            $aforms['userScriptsPath']->getData(),
            $aforms['ackAccessRole']->getData(),
        );
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ConfigGeneral::class,
        ));
    }
}
