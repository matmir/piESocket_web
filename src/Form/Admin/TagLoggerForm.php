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
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use App\Entity\AppException;
use App\Entity\Admin\TagLogger;
use App\Entity\Admin\TagLoggerInterval;
use App\Service\Admin\TagsMapper;

/**
 * Form class for write Tag logger
 *
 * @author Mateusz Mirosławski
 */
class TagLoggerForm extends AbstractType implements DataMapperInterface
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
        $builder->add('ltid', HiddenType::class, array('constraints' => [
                                                new PositiveOrZero()
                                            ],
                                            'empty_data' => '0'))
            ->add('ltTagName', TextType::class, array('label' => 'Tag name',
                                            'constraints' => [
                                                new NotBlank(),
                                                new Length(['max' => 50]),
                                            ],
                                            'empty_data' => ''))
            ->add('ltInterval', ChoiceType::class, array('label' => 'Log interval',
                                            'choices'  => array(
                                                TagLoggerInterval::N_I_100MS => TagLoggerInterval::I_100MS,
                                                TagLoggerInterval::N_I_200MS => TagLoggerInterval::I_200MS,
                                                TagLoggerInterval::N_I_500MS => TagLoggerInterval::I_500MS,
                                                TagLoggerInterval::N_I_1S => TagLoggerInterval::I_1S,
                                                TagLoggerInterval::N_I_XS => TagLoggerInterval::I_XS,
                                                TagLoggerInterval::N_I_ON_CHANGE => TagLoggerInterval::I_ON_CHANGE,
                                            ),
                                            'constraints' => [
                                                new NotBlank(),
                                                new Range(['min' => 1,
                                                            'max' => 6]),
                                            ],
                                            'empty_data' => '1'))
            ->add('ltIntervalS', IntegerType::class, array('label' => 'Seconds interval',
                                            'constraints' => [
                                                new PositiveOrZero()
                                            ],
                                            'empty_data' => '0'))
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ->setDataMapper($this);
    }
    
    /**
     * @param TagLogger|null $viewData
     */
    public function mapDataToForms($viewData, $forms)
    {
        // there is no data yet, so nothing to prepopulate
        if (null === $viewData) {
            return;
        }

        // invalid data type
        if (!$viewData instanceof TagLogger) {
            throw new UnexpectedTypeException($viewData, TagLogger::class);
        }

        /** @var FormInterface[] $forms */
        $aforms = iterator_to_array($forms);

        // initialize form field values
        $aforms['ltid']->setData($viewData->getId());
        // Tag name
        if ($viewData->isTag()) {
            $aforms['ltTagName']->setData($viewData->getTag()->getName());
        } else {
            $aforms['ltTagName']->setData('');
        }
        $aforms['ltInterval']->setData($viewData->getInterval());
        $aforms['ltIntervalS']->setData($viewData->getIntervalS());
    }

    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $aforms = iterator_to_array($forms);
        
        // Get tag object
        $tag = null;
        if ($aforms['ltTagName']->getData() != '') {
            try {
                $tag = $this->tagMapper->getTagByName($aforms['ltTagName']->getData());
            } catch (AppException $ex) {
                if ($ex->getCode() == AppException::TAG_NOT_EXIST) {
                    $aforms['ltTagName']->addError(new FormError($ex->getMessage()));
                }
            }
        }

        $viewData = new TagLogger(
            $tag,
            $aforms['ltid']->getData(),
            $aforms['ltInterval']->getData(),
            $aforms['ltIntervalS']->getData(),
        );
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TagLogger::class,
        ));
    }
}
