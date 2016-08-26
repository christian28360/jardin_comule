<?php

namespace DTC\Modules\GestionReservationAuto\Form;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form
 *
 * @author wug870
 */
class ReservationForUserForm extends ReservationForm
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->user;

        parent::buildForm($builder, $options);

        $builder->add('user', 'entity', array(
            'label' => 'Utilisateur :',
            'class' => '\DTC\Modules\GestionReservationAuto\Entity\User',
            'em' => 'gestionReservationAuto',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($user) {
                return $er->getQBUserService($user->getSourceAgent()->getSourceOrgaAffectectation());
            }
        ));
    }

    public function getName()
    {
        return "Reservation";
    }

}
