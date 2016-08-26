<?php

namespace DTC\Modules\GestionReservationAuto\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form
 *
 * @author wug870
 */
class RegistreForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('dateDebut', 'date', array(
            'label'=>'Date de début :',
            'widget'=>'single_text',
            'format' => 'dd/MM/yyyy',
        ));
        $builder->add('dateFin', 'date', array(
            'label'=>'Date de fin :',
            'widget'=>'single_text',
            'format' => 'dd/MM/yyyy',
        ));
        
    }

    public function getName() {
        return "Registre";
    }
}