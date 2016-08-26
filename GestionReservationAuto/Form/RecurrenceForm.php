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
class RecurrenceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('dateDebut', 'datetime', array(
            'label' => 'Date de début :',
            'date_widget' => 'single_text',
            'date_format' => 'dd/MM/yyyy',
            'hours' => range(5, 21),
            'required' => true,
        ));

        $builder->add('dateFin', 'datetime', array(
            'label' => 'Date de fin :',
            'date_widget' => 'single_text',
            'date_format' => 'dd/MM/yyyy',
            'hours' => range(5, 21),
            'required' => true,
        ));
        $builder->add('recurrence', 'choice', array(
            'label'=>'Récurrence :',
            'choices'=> array('quotidien'=>'Quotidien','hebdomadaire'=>'Hebdomadaire','mensuelle'=>'Mensuelle')
        ));
        $builder->add('nbRecurrence', 'integer', array(
            'label'=>'Nombre de récurrences :',
            'attr'=>array('max'=>52)
        ));
        $builder->add('destination', 'text', array(
            'label'=>'Destination :'
        ));
        $builder->add('distance', 'text', array(
            'label'=>'Distance aller/retour Km/J :'
        ));
        
        $builder->add('depart', 'text', array(
            'label'=>'Départ :'
        ));
        $builder->add('motif', 'text', array(
            'label'=>'Motif :'
        ));
        
        $builder->add('save', 'submit', array(
            'label' => 'Valider',
            'attr' => array(
                'class' => 'btn btn-primary',
            ),
        ));
    }

    public function getName() {
        return "Service";
    }
}