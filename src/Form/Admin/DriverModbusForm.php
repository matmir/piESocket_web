<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Range;
use App\Entity\Admin\DriverConnection;
use App\Entity\Admin\DriverModbus;
use App\Entity\Admin\DriverModbusMode;
use App\Entity\Admin\DriverType;

/**
 * Form class for ModbusTCP driver configuration
 *
 * @author Mateusz Mirosławski
 */
class DriverModbusForm extends AbstractType implements DataMapperInterface
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('connId', HiddenType::class, array('constraints' => [
                                                new PositiveOrZero()
                                            ]))
            ->add('connName', TextType::class, array('label' => 'Connection name',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Length(['max' => 100]),
                                            ]))
            ->add('id', HiddenType::class, array('constraints' => [
                                                new PositiveOrZero()
                                            ]))
            ->add('mode', ChoiceType::class, array('label' => 'Mode',
                                            'choices'  => array(
                                                    DriverModbusMode::N_TCP => DriverModbusMode::TCP,
                                                    DriverModbusMode::N_RTU => DriverModbusMode::RTU,
                                            ),
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => 0,
                                                            'max' => 1]),
                                            ]
                                            ))
            ->add('registerCount', IntegerType::class, array('label' => 'Registers to read [words]',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => 1,
                                                            'max' => 4096]),
                                            ]))
            ->add('driverPolling', IntegerType::class, array('label' => 'Driver polling interval [ms]',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => 10,
                                                            'max' => 5000]),
                                            ]))
            ->add('TCP_addr', TextType::class, array('label' => 'Slave IP address',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Length(['max' => 15]),
                                            ]))
            ->add('TCP_port', IntegerType::class, array('label' => 'Port',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => 1,
                                                            'max' => 65535]),
                                            ]))
            ->add('TCP_use_slaveID', ChoiceType::class, array('label' => 'Use SlaveID',
                                            'choices'  => array(
                                                'No' => 0,
                                                'Yes' => 1,
                                            ),
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => 0,
                                                            'max' => 1]),
                                            ]
                                            ))
            ->add('slaveID', IntegerType::class, array('label' => 'Slave ID',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => 1,
                                                            'max' => 247]),
                                            ]))
            ->add('RTU_port', TextType::class, array('label' => 'COM port name',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Length(['max' => 200]),
                                            ]))
            ->add('RTU_baud', IntegerType::class, array('label' => 'COM baud rate',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => 1,
                                                            'max' => 1000000]),
                                            ]))
            ->add('RTU_parity', ChoiceType::class, array('label' => 'Parity',
                                            'choices'  => array(
                                                'none' => 'N',
                                                'even' => 'E',
                                                'odd' => 'O',
                                            ),
                                            'constraints' => [
                                                new NotBlank(),
                                                new Length(['max' => 1]),
                                            ]
                                            ))
            ->add('RTU_dataBit', ChoiceType::class, array('label' => 'Data bit',
                                            'choices'  => array(
                                                '5' => 5,
                                                '6' => 6,
                                                '7' => 7,
                                                '8' => 8,
                                            ),
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => 5,
                                                            'max' => 8]),
                                            ]
                                            ))
            ->add('RTU_stopBit', ChoiceType::class, array('label' => 'Stop bit',
                                            'choices'  => array(
                                                '1' => 1,
                                                '2' => 2,
                                            ),
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => 1,
                                                            'max' => 2]),
                                            ]
                                            ))
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ->setDataMapper($this);
    }
    
    /**
     * @param DriverConnection|null $viewData
     */
    public function mapDataToForms($viewData, $forms)
    {
        // there is no data yet, so nothing to prepopulate
        if (null === $viewData) {
            return;
        }

        // invalid data type
        if (!$viewData instanceof DriverConnection) {
            throw new UnexpectedTypeException($viewData, DriverConnection::class);
        }

        /** @var FormInterface[] $forms */
        $aforms = iterator_to_array($forms);

        // initialize form field values
        $aforms['connId']->setData($viewData->getId());
        $aforms['connName']->setData($viewData->getName());
        
        // Modbus object
        if ($viewData->isModbusConfig()) {
            $mb = $viewData->getModbusConfig();
            
            $aforms['id']->setData($mb->getId());
            $aforms['mode']->setData($mb->getMode());
            $aforms['registerCount']->setData($mb->getRegisterCount());
            $aforms['driverPolling']->setData($mb->getDriverPolling());
            $aforms['TCP_addr']->setData($mb->getTCPaddr());
            $aforms['TCP_port']->setData($mb->getTCPport());
            $aforms['TCP_use_slaveID']->setData($mb->useSlaveIdInTCP());
            $aforms['slaveID']->setData($mb->getSlaveID());
            $aforms['RTU_port']->setData($mb->getRTUport());
            $aforms['RTU_baud']->setData($mb->getRTUbaud());
            $aforms['RTU_parity']->setData($mb->getRTUparity());
            $aforms['RTU_dataBit']->setData($mb->getRTUdataBit());
            $aforms['RTU_stopBit']->setData($mb->getRTUstopBit());
        } else {
            throw new Exception("Missing Modbus configuration");
        }
    }

    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $aforms = iterator_to_array($forms);
        
        // Create modbus object
        $mb = new DriverModbus(
            $aforms['id']->getData(),
            $aforms['mode']->getData(),
            $aforms['registerCount']->getData(),
            $aforms['driverPolling']->getData(),
            $aforms['slaveID']->getData(),
            $aforms['TCP_addr']->getData(),
            $aforms['TCP_port']->getData(),
            $aforms['TCP_use_slaveID']->getData(),
            $aforms['RTU_port']->getData(),
            $aforms['RTU_baud']->getData(),
            $aforms['RTU_parity']->getData(),
            $aforms['RTU_dataBit']->getData(),
            $aforms['RTU_stopBit']->getData(),
        );
        
        // Create object
        $viewData = new DriverConnection(
            $aforms['connId']->getData(),
            $aforms['connName']->getData(),
            DriverType::MODBUS,
            $mb
        );
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => DriverConnection::class,
        ));
    }
}
