<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use App\Entity\Admin\DriverConnection;
use App\Entity\Admin\DriverSHM;
use App\Entity\Admin\DriverType;

/**
 * Form class for SHM driver configuration
 *
 * @author Mateusz Mirosławski
 */
class DriverSHMForm extends AbstractType implements DataMapperInterface
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
                                            ],
                                            'empty_data' => '',
                                            ))
            ->add('id', HiddenType::class, array('constraints' => [
                                                new PositiveOrZero()
                                            ]))
            ->add('segmentName', TextType::class, array('label' => 'Shared memory segment name',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Length(['max' => 200]),
                                            ],
                                            'empty_data' => ''
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
        
        // SHM object
        if ($viewData->isShmConfig()) {
            $shm = $viewData->getShmConfig();
            
            $aforms['id']->setData($shm->getId());
            $aforms['segmentName']->setData($shm->getSegmentName());
        } else {
            throw new Exception("Missing SHM configuration");
        }
    }

    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $aforms = iterator_to_array($forms);
        
        // Create modbus object
        $shm = new DriverSHM(
            $aforms['id']->getData(),
            $aforms['segmentName']->getData()
        );
        
        // Create object
        $viewData = new DriverConnection(
            $aforms['connId']->getData(),
            $aforms['connName']->getData(),
            DriverType::SHM,
            null,
            $shm
        );
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => DriverConnection::class,
        ));
    }
}
