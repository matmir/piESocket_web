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
        $builder->add('tid', HiddenType::class)
            ->add('tConnId', ChoiceType::class, array('choices'  => $options['connections'],
                                                    'label' => 'Connection'
                                            ))
            ->add('tName', TextType::class, array('label' => 'Name'))
            ->add('tType', ChoiceType::class, array('choices'  => array(
                                                        TagType::N_BIT => TagType::BIT,
                                                        TagType::N_BYTE => TagType::BYTE,
                                                        TagType::N_WORD => TagType::WORD,
                                                        TagType::N_DWORD => TagType::DWORD,
                                                        TagType::N_INT => TagType::INT,
                                                        TagType::N_REAL => TagType::REAL,
                                                        ),
                                                    'label' => 'Tag type'
                                            ))
            ->add('tArea', ChoiceType::class, array('choices'  => array(
                                                        TagArea::N_INPUT => TagArea::INPUT,
                                                        TagArea::N_OUTPUT => TagArea::OUTPUT,
                                                        TagArea::N_MEMORY => TagArea::MEMORY,
                                                        ),
                                                    'label' => 'Area'
                                            ))
            ->add('tByteAddress', IntegerType::class, array('label' => 'Byte address'))
            ->add('tBitAddress', ChoiceType::class, array('choices'  => array(
                                                            '0' => 0,
                                                            '1' => 1,
                                                            '2' => 2,
                                                            '3' => 3,
                                                            '4' => 4,
                                                            '5' => 5,
                                                            '6' => 6,
                                                            '7' => 7,
                                                            ),
                                                        'label' => 'Bit address'
                                            ))
            ->add('tReadAccess', ChoiceType::class, array('choices'  => array(
                                                        'ADMIN' => 'ROLE_ADMIN',
                                                        'USER' => 'ROLE_USER',
                                                        'GUEST' => 'ROLE_GUEST',
                                                        ),
                                                    'label' => 'Read access'
                                            ))
            ->add('tWriteAccess', ChoiceType::class, array('choices'  => array(
                                                        'ADMIN' => 'ROLE_ADMIN',
                                                        'USER' => 'ROLE_USER',
                                                        'GUEST' => 'ROLE_GUEST',
                                                        ),
                                                    'label' => 'Write access'
                                            ))
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ->setDataMapper($this);
    }
    
    /**
     * @param Color|null $viewData
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
            $aforms['tConnId']->getData(),
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
