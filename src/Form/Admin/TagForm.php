<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use App\Entity\Admin\Tag;
use App\Entity\Admin\TagArea;
use App\Entity\Admin\TagType;

/**
 * Form class for write Tag
 *
 * @author Mateusz Mirosławski
 */
class TagForm extends AbstractType implements DataMapperInterface
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tid', HiddenType::class, array('constraints' => [
                                                new PositiveOrZero()
                                            ],
                                            'empty_data' => '0'))
            ->add('tConnId', ChoiceType::class, array('label' => 'Connection',
                                        'choices'  => $options['connections'],
                                        'constraints' => [
                                            new NotBlank(),
                                            new Positive()
                                        ],
                                        'empty_data' => '1'))
            ->add('tName', TextType::class, array('label' => 'Name',
                                        'constraints' => [
                                            new NotBlank(),
                                            new Length(['max' => 50]),
                                            new Regex(['pattern' => "/[^A-Za-z0-9_]/",
                                                        'match' => false,
                                                        'message' => "Tag name contain invalid characters"])
                                        ],
                                        'empty_data' => ''))
            ->add('tType', ChoiceType::class, array('label' => 'Tag type',
                                        'choices'  => array(
                                            TagType::N_BIT => TagType::BIT,
                                            TagType::N_BYTE => TagType::BYTE,
                                            TagType::N_WORD => TagType::WORD,
                                            TagType::N_DWORD => TagType::DWORD,
                                            TagType::N_INT => TagType::INT,
                                            TagType::N_REAL => TagType::REAL,
                                        ),
                                        'constraints' => [
                                            new NotBlank(),
                                            new Range(['min' => 1,
                                                        'max' => 6]),
                                        ],
                                        'empty_data' => '1'))
            ->add('tArea', ChoiceType::class, array('label' => 'Area',
                                        'choices'  => array(
                                            TagArea::N_INPUT => TagArea::INPUT,
                                            TagArea::N_OUTPUT => TagArea::OUTPUT,
                                            TagArea::N_MEMORY => TagArea::MEMORY,
                                        ),
                                        'constraints' => [
                                            new NotBlank(),
                                            new Range(['min' => 1,
                                                        'max' => 3]),
                                        ],
                                        'empty_data' => '1'))
            ->add('tByteAddress', IntegerType::class, array('label' => 'Byte address',
                                        'constraints' => [
                                            new NotBlank(),
                                            new Range(['min' => 0]),
                                        ],
                                        'empty_data' => '0'))
            ->add('tBitAddress', ChoiceType::class, array('label' => 'Bit address',
                                        'choices'  => array(
                                            '0' => 0,
                                            '1' => 1,
                                            '2' => 2,
                                            '3' => 3,
                                            '4' => 4,
                                            '5' => 5,
                                            '6' => 6,
                                            '7' => 7,
                                        ),
                                        'constraints' => [
                                            new NotBlank(),
                                            new Range(['min' => 0,
                                                        'max' => 7]),
                                        ],
                                        'empty_data' => '0'))
            ->add('tReadAccess', ChoiceType::class, array('label' => 'Read access',
                                        'choices'  => array(
                                            'ADMIN' => 'ROLE_ADMIN',
                                            'USER' => 'ROLE_USER',
                                            'GUEST' => 'ROLE_GUEST',
                                        ),
                                        'constraints' => [
                                            new NotBlank(),
                                            new Length(['max' => 20]),
                                        ],
                                        'empty_data' => 'ROLE_ADMIN'))
            ->add('tWriteAccess', ChoiceType::class, array('label' => 'Write access',
                                        'choices'  => array(
                                            'ADMIN' => 'ROLE_ADMIN',
                                            'USER' => 'ROLE_USER',
                                            'GUEST' => 'ROLE_GUEST',
                                        ),
                                        'constraints' => [
                                            new NotBlank(),
                                            new Length(['max' => 20]),
                                        ],
                                        'empty_data' => 'ROLE_ADMIN'))
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ->setDataMapper($this);
    }
    
    /**
     * @param Tag|null $viewData
     */
    public function mapDataToForms($viewData, $forms)
    {
        // there is no data yet, so nothing to prepopulate
        if (null === $viewData) {
            return;
        }

        // invalid data type
        if (!$viewData instanceof Tag) {
            throw new UnexpectedTypeException($viewData, Tag::class);
        }

        /** @var FormInterface[] $forms */
        $aforms = iterator_to_array($forms);

        // initialize form field values
        $aforms['tid']->setData($viewData->getId());
        $aforms['tConnId']->setData($viewData->getConnId());
        $aforms['tName']->setData($viewData->getName());
        $aforms['tType']->setData($viewData->getType());
        $aforms['tArea']->setData($viewData->getArea());
        $aforms['tByteAddress']->setData($viewData->getByteAddress());
        $aforms['tBitAddress']->setData($viewData->getBitAddress());
        $aforms['tReadAccess']->setData($viewData->getReadAccess());
        $aforms['tWriteAccess']->setData($viewData->getWriteAccess());
    }

    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $aforms = iterator_to_array($forms);

        $viewData = new Tag(
            $aforms['tid']->getData(),
            ($aforms['tConnId']->getData() === null) ? (0) : ($aforms['tConnId']->getData()),
            '',
            $aforms['tName']->getData(),
            $aforms['tType']->getData(),
            $aforms['tArea']->getData(),
            $aforms['tByteAddress']->getData(),
            $aforms['tBitAddress']->getData(),
            $aforms['tReadAccess']->getData(),
            $aforms['tWriteAccess']->getData()
        );
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Tag::class,
            'connections' => array(),
        ));
    }
}
