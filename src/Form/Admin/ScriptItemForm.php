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
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use App\Entity\AppException;
use App\Entity\Admin\ScriptItem;
use App\Service\Admin\TagsMapper;

/**
 * Form class for write Script item
 *
 * @author Mateusz Mirosławski
 */
class ScriptItemForm extends AbstractType implements DataMapperInterface
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
        $builder->add('scid', HiddenType::class, array('constraints' => [
                                                new PositiveOrZero()
                                            ],
                                            'empty_data' => '0'))
            ->add('scTagName', TextType::class, array('label' => 'Tag name',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Length(['max' => 50]),
                                            ],
                                            'empty_data' => ''))
            ->add('scName', TextType::class, array('label' => 'Script name',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Length(['max' => 50]),
                                            ],
                                            'empty_data' => ''))
            ->add('scFeedbackRun', TextType::class, array('label' => 'Feedback tag (script running)',
                                            'required' => false,
                                            'constraints' => [
                                                new Length(['max' => 50]),
                                            ],
                                            'empty_data' => ''))
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ->setDataMapper($this);
    }
    
    /**
     * @param ScriptItem|null $viewData
     */
    public function mapDataToForms($viewData, $forms)
    {
        // there is no data yet, so nothing to prepopulate
        if (null === $viewData) {
            return;
        }

        // invalid data type
        if (!$viewData instanceof ScriptItem) {
            throw new UnexpectedTypeException($viewData, ScriptItem::class);
        }

        /** @var FormInterface[] $forms */
        $aforms = iterator_to_array($forms);

        // initialize form field values
        $aforms['scid']->setData($viewData->getId());
        // Tag name
        if ($viewData->isTag()) {
            $aforms['scTagName']->setData($viewData->getTag()->getName());
        } else {
            $aforms['scTagName']->setData('');
        }
        // Script name
        $aforms['scName']->setData($viewData->getName());
        // Feedback Tag name
        if ($viewData->isFeedbackRun()) {
            $aforms['scFeedbackRun']->setData($viewData->getFeedbackRun()->getName());
        } else {
            $aforms['scFeedbackRun']->setData('');
        }
    }

    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $aforms = iterator_to_array($forms);
        
        // Get script tag object
        $sTag = null;
        if ($aforms['scTagName']->getData() != '') {
            try {
                $sTag = $this->tagMapper->getTagByName($aforms['scTagName']->getData());
            } catch (AppException $ex) {
                if ($ex->getCode() == AppException::TAG_NOT_EXIST) {
                    $aforms['scTagName']->addError(new FormError($ex->getMessage()));
                }
            }
        }
        
        // Get script feedback tag object
        $fbTag = null;
        if ($aforms['scFeedbackRun']->getData() != '') {
            try {
                $fbTag = $this->tagMapper->getTagByName($aforms['scFeedbackRun']->getData());
            } catch (AppException $ex) {
                if ($ex->getCode() == AppException::TAG_NOT_EXIST) {
                    $aforms['scFeedbackRun']->addError(new FormError($ex->getMessage()));
                }
            }
        }

        // Create object
        try {
            $viewData = new ScriptItem(
                $sTag,
                $fbTag,
                $aforms['scid']->getData(),
                $aforms['scName']->getData()
            );
        } catch (AppException $ex) {
            if ($ex->getCode() == AppException::TAG_WRONG_TYPE) {
                $msg = $ex->getMessage();
                if (strpos($msg, $aforms['scTagName']->getData()) !== false) {
                    $aforms['scTagName']->addError(new FormError($ex->getMessage()));
                } else {
                    $aforms['scFeedbackRun']->addError(new FormError($ex->getMessage()));
                }
            }
        }
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ScriptItem::class,
        ));
    }
}
