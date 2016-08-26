<?php

namespace DTC\Modules\GestionReservationAuto\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use DTC\Modules\GestionReservationAuto\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Form
 *
 * @author wug870
 */
class ReservationForm extends AbstractType
{

    /**
     * @var \DTC\Modules\GestionReservationAuto\Entity\User
     */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->user;
        $minutes = \DTC\Modules\GestionReservationAuto\Entity\Reservation::getMinutesAutorisees();

        $builder->add('dateDebut', 'date', array(
            'label' => 'Date prise ',
            'required' => true,
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'dd/MM/yyyy HH:mm',
            'attr' => array('class' => 'form-control datepicker-widget dateDebut')
        ));

        $builder->add('dateFin', 'date', array(
            'label' => 'Date restitution ',
            'required' => true,
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'dd/MM/yyyy HH:mm',
            'attr' => array('class' => 'form-control datepicker-widget dateFin')
        ));

//        $builder->add('dateDebut', 'datetime', array(
//            'label' => false,
//            'date_widget' => 'single_text',
//            'date_format' => 'dd/MM/yyyy',
//            'minutes' => $minutes,
//            'required' => true,
//        ));
//
//        $builder->add('dateFin', 'datetime', array(
//            'label' => false,
//            'date_widget' => 'single_text',
//            'date_format' => 'dd/MM/yyyy',
//            'minutes' => $minutes,
//            'required' => true,
//        ));

        $builder->add('voiture', 'entity', array(
            'label' => 'Voiture :',
            'required' => false,
            'class' => '\DTC\Modules\GestionReservationAuto\Entity\Voiture',
            'em' => 'gestionReservationAuto',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($user) {
                return $er->getQBByUserPerimetre($user);
            }
        ));

        $builder->add('depart', 'text', array(
            'label' => 'Départ :'
        ));

        $builder->add('destination', 'text', array(
            'label' => 'Destination :'
        ));

        $builder->add('distance', 'text', array(
            'label' => 'Distance aller/retour Km',
            'max_length' => 3
        ));

        $builder->add('motif', 'text', array(
            'label' => 'Motif :'
        ));

        $builder->add('accompagnant1', 'text', array(
            'label' => 'Nom accompagnant 1 :',
            'required' => false,
        ));

        $builder->add('accompagnant2', 'text', array(
            'label' => 'Nom accompagnant 2 :',
            'required' => false,
        ));

        $builder->add('accompagnant3', 'text', array(
            'label' => 'Nom accompagnant 3 :',
            'required' => false,
        ));

        $builder->add('accompagnant4', 'text', array(
            'label' => 'Nom accompagnant 4 :',
            'required' => false,
        ));

        $builder->add('remisage', 'checkbox', array(
            'label' => 'Remisage à domicile :',
            'required' => false,
            'constraints' => array(new Assert\Type(array('type' => 'boolean'))),
        ));

        $builder->add('signatureValidateur', 'text', array(
            'label' => 'Mail du validateur :',
            'required' => false,
        ));

        $builder->add('signatureSuperieur', 'text', array(
            'label' => 'Mail supérieur hiérarchique :',
            'required' => true,
            'disabled' => true,
        ));

//        $builder->add('signatureSuperieur', 'entity', array(
//            'label' => 'Mail sup. hiérarchique :',
//            'disabled' => true,
//            'required' => true,
//            'class' => '\DTC\Modules\GestionReservationAuto\Entity\User',
//            'property' => 'MailSuperieur',
//            'em' => 'gestionReservationAuto',
//            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
//                return $er->createQueryBuilder('o');
//            }
//        ));

        $builder->add('save', 'submit', array(
            'label' => 'Valider',
            'attr' => array(
                'class' => 'btn btn-primary

        ',
            ),
        ));
    }

    public function getName()
    {
        return "Reservation";
    }

}
