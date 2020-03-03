<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use App\Entity\Admin\ScriptItemEntity;

/**
 * Form class for write Script item
 *
 * @author Mateusz Mirosławski
 */
class ScriptItemForm extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('scid', HiddenType::class)
            ->add('scTagName', null, array('label' => 'Tag name'))
            ->add('scName', null, array('label' => 'Script name'))
            ->add('scFeedbackRun', null, array('label' => 'Feedback tag (script running)'))
            ->add('save', SubmitType::class, array('label' => 'Save'));
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        
        $resolver->setDefaults(array(
            'data_class' => ScriptItemEntity::class,
        ));
    }
}
