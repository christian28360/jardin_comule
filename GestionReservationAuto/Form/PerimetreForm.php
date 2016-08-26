<?php

namespace DTC\Modules\GestionReservationAuto\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Form
 *
 * @author wug870
 */
class PerimetreForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('libelle', 'text', array(
            'label'=>'Nom du périmètre :',
            'attr' => array(
                'class' => 'form-control',
            ),
        ));
    }

    public function getName() {
        return "Perimetre";
    }
}