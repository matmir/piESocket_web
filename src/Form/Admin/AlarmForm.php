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
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Range;
use App\Entity\AppException;
use App\Entity\Admin\Alarm;
use App\Entity\Admin\AlarmTrigger;
use App\Service\Admin\TagsMapper;

/**
 * Form class for write Alarm
 *
 * @author Mateusz Mirosławski
 */
class AlarmForm extends AbstractType implements DataMapperInterface
{
    /**
     * Tag mapper
     */
    private $tagMapper;
    
    public function __construct(TagsMapper $tm)
    {
        $this->tagMapper = $tm;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('adid', HiddenType::class, array('constraints' => [
                                                new PositiveOrZero()
                                            ],
                                            'empty_data' => '0'))
            ->add('adTagName', TextType::class, array('label' => 'Tag name',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Length(['max' => 50]),
                                            ],
                                            'empty_data' => ''))
            ->add('adPriority', IntegerType::class, array('label' => 'Priority',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => 1,
                                                            'max' => 5]),
                                            ],
                                            'empty_data' => '0'))
            ->add('adMessage', TextType::class, array('label' => 'Message',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Length(['max' => 200]),
                                            ],
                                            'empty_data' => ''))
            ->add('adTrigger', ChoiceType::class, array('label' => 'Trigger mode',
                                            'choices'  => array(
                                                AlarmTrigger::N_TR_BIN => AlarmTrigger::TR_BIN,
                                                AlarmTrigger::N_TR_TAG_GT_VAL => AlarmTrigger::TR_TAG_GT_VAL,
                                                AlarmTrigger::N_TR_TAG_LT_VAL => AlarmTrigger::TR_TAG_LT_VAL,
                                                AlarmTrigger::N_TR_TAG_GTE_VAL => AlarmTrigger::TR_TAG_GTE_VAL,
                                                AlarmTrigger::N_TR_TAG_LTE_VAL => AlarmTrigger::TR_TAG_LTE_VAL,
                                                AlarmTrigger::N_TR_TAG_EQ_VAL => AlarmTrigger::TR_TAG_EQ_VAL,
                                                AlarmTrigger::N_TR_TAG_NEQ_VAL => AlarmTrigger::TR_TAG_NEQ_VAL
                                            ),
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => 1,
                                                            'max' => 7]),
                                            ],
                                            'empty_data' => '1'
                                            ))
            ->add('adTriggerB', ChoiceType::class, array('label' => 'Binary value',
                                            'choices'  => array(
                                                            'false' => 0,
                                                            'true' => 1
                                            ),
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => 0,
                                                            'max' => 1]),
                                            ],
                                            'empty_data' => '0'
                                            ))
            ->add('adTriggerN', IntegerType::class, array('label' => 'Numeric value',
                                            'constraints' => [
                                                new NotBlank()
                                            ],
                                            'empty_data' => '0'
                                            ))
            ->add('adTriggerR', NumberType::class, array('label' => 'Real value',
                                            'constraints' => [
                                                new NotBlank()
                                            ],
                                            'empty_data' => '0'
                                            ))
            ->add('adAutoAck', ChoiceType::class, array('label' => 'Auto ack',
                                            'choices'  => array(
                                                            'false' => 0,
                                                            'true' => 1
                                            ),
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => 0,
                                                            'max' => 1]),
                                            ],
                                            'empty_data' => '0'
                                            ))
            ->add('adFeedbackNotACK', TextType::class, array('label' => 'Feedback tag (alarm not ack)',
                                            'required' => false,
                                            'constraints' => [
                                                new Length(['max' => 50]),
                                            ],
                                            'empty_data' => ''
                                            ))
            ->add('adHWAck', TextType::class, array('label' => 'Tag for alarm ack',
                                            'required' => false,
                                            'constraints' => [
                                                new Length(['max' => 50]),
                                            ],
                                            'empty_data' => ''
                                            ))
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ->setDataMapper($this);
    }
    
    /**
     * @param Alarm|null $viewData
     */
    public function mapDataToForms($viewData, $forms)
    {
        // there is no data yet, so nothing to prepopulate
        if (null === $viewData) {
            return;
        }

        // invalid data type
        if (!$viewData instanceof Alarm) {
            throw new UnexpectedTypeException($viewData, Alarm::class);
        }

        /** @var FormInterface[] $forms */
        $aforms = iterator_to_array($forms);

        // initialize form field values
        $aforms['adid']->setData($viewData->getId());
        // Tag name
        if ($viewData->isTag()) {
            $aforms['adTagName']->setData($viewData->getTag()->getName());
        } else {
            $aforms['adTagName']->setData('');
        }
        
        $aforms['adPriority']->setData($viewData->getPriority());
        $aforms['adMessage']->setData($viewData->getMessage());
        $aforms['adTrigger']->setData($viewData->getTrigger());
        $aforms['adTriggerB']->setData($viewData->getTriggerBin());
        $aforms['adTriggerN']->setData($viewData->getTriggerNumeric());
        $aforms['adTriggerR']->setData($viewData->getTriggerReal());
        $aforms['adAutoAck']->setData($viewData->isAutoAck());
        
        // Feedback not ack Tag name
        if ($viewData->isFeedbackNotAck()) {
            $aforms['adFeedbackNotACK']->setData($viewData->getFeedbackNotAck()->getName());
        } else {
            $aforms['adFeedbackNotACK']->setData('');
        }
        // HW ack Tag name
        if ($viewData->isHWAck()) {
            $aforms['adHWAck']->setData($viewData->getHWAck()->getName());
        } else {
            $aforms['adHWAck']->setData('');
        }
    }

    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $aforms = iterator_to_array($forms);
        
        // Get alarm tag object
        $aTag = null;
        if ($aforms['adTagName']->getData() != '') {
            try {
                $aTag = $this->tagMapper->getTagByName($aforms['adTagName']->getData());
            } catch (AppException $ex) {
                if ($ex->getCode() == AppException::TAG_NOT_EXIST) {
                    $aforms['adTagName']->addError(new FormError($ex->getMessage()));
                }
            }
        }
        
        // Get alarm feedback tag object
        $fbTag = null;
        if ($aforms['adFeedbackNotACK']->getData() != '') {
            try {
                $fbTag = $this->tagMapper->getTagByName($aforms['adFeedbackNotACK']->getData());
            } catch (AppException $ex) {
                if ($ex->getCode() == AppException::TAG_NOT_EXIST) {
                    $aforms['adFeedbackNotACK']->addError(new FormError($ex->getMessage()));
                }
            }
        }
        
        // Get hw ack tag object
        $hwTag = null;
        if ($aforms['adHWAck']->getData() != '') {
            try {
                $hwTag = $this->tagMapper->getTagByName($aforms['adHWAck']->getData());
            } catch (AppException $ex) {
                if ($ex->getCode() == AppException::TAG_NOT_EXIST) {
                    $aforms['adHWAck']->addError(new FormError($ex->getMessage()));
                }
            }
        }

        // Create object
        try {
            $viewData = new Alarm(
                $aTag,
                $fbTag,
                $hwTag,
                $aforms['adid']->getData(),
                $aforms['adPriority']->getData(),
                $aforms['adMessage']->getData(),
                $aforms['adTrigger']->getData(),
                $aforms['adTriggerB']->getData(),
                $aforms['adTriggerN']->getData(),
                $aforms['adTriggerR']->getData(),
                $aforms['adAutoAck']->getData()
            );
        } catch (AppException $ex) {
            if ($ex->getCode() == AppException::TAG_WRONG_TYPE) {
                $msg = $ex->getMessage();
                $dt = $aforms['adFeedbackNotACK']->getData();
                $needle = ($dt == '') ? ('none') : ($dt);
                if (strpos($msg, $needle) !== false) {
                    $aforms['adFeedbackNotACK']->addError(new FormError($ex->getMessage()));
                } else {
                    $aforms['adHWAck']->addError(new FormError($ex->getMessage()));
                }
            }
        }
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Alarm::class,
        ));
    }
}
