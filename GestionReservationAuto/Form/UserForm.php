<?php

namespace DTC\Modules\GestionReservationAuto\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use DTC\Modules\GestionReservationAuto\Entity\Service;

/**
 * Form
 *
 * @author wug870
 */
class UserForm extends AbstractType
{

    private $lesVoitures;

    /**
     * @var \DTC\Modules\GestionReservationAuto\Entity\User
     */
    private $user;
    private $updateMode;

    public function __construct($update = false, $lesVoitures = null, \DTC\Modules\GestionReservationAuto\Entity\User $user = null)
    {
        $this->updateMode = $update;
        $this->lesVoitures = $lesVoitures;
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->user;

        $builder->add('adresse', 'text', array(
            'label' => 'Adresse :'
        ));

        $builder->add('cp', 'text', array(
            'label' => 'Code Postal :',
            'constraints' => array(new Assert\Type(array('type' => 'numeric', 'message' => 'Le code postal ne doit comporter que des chiffres'))),
        ));

        $builder->add('ville', 'text', array(
            'label' => 'Ville :',
        ));

        $builder->add('telephone', 'text', array(
            'label' => 'Téléphone :',
        ));

        $builder->add('distanceDT', 'number', array(
            'label' => 'Distance travail/domicile (en km) :',
            'constraints' => array(new Assert\Type(array('type' => 'numeric', 'message' => 'Saisir un nombre entier de kilomètres'))),
        ));

        $builder->add('perimetre', 'entity', array(
            'class' => '\DTC\Modules\GestionReservationAuto\Entity\Perimetre',
            'em' => 'gestionReservationAuto',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($user) {
                if ($user->getGrade() == 'admin') {
                    return $er->createQueryBuilder('u');
                } else {
                    return $er->getPerimetresByOneService($user->getSourceAgent()
                                            ->getSourceOrgaAffectectation());
                }
            },
            'property' => 'libelle',
            'required' => false,
        ));

        if ($this->updateMode) {
            $grades = array();

            if ($user->getGrade() == 'admin') {
                $grades['admin'] = 'Administrateur';
            }

            $grades = $grades + array(
                'gestionnaire' => 'Gestionnaire',
                'utilisateur' => 'Utilisateur',
            );

            $builder->add('grade', 'choice', array(
                'choices' => $grades,
                'preferred_choices' => array('utilisateur'),
            ));

            $builder->add('voitureAttribue', 'entity', array(
                'class' => '\DTC\Modules\GestionReservationAuto\Entity\Voiture',
                'em' => 'gestionReservationAuto',
                'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('p')
                                    ->join('\DTC\Modules\GestionReservationAuto\Entity\Vehicule\Vehicule', 'v', 'WITH', 'p.vehicule = v')
                                    ->where('v.locSourceOrga = :so')
                                    ->andWhere('p.statut = :statut')
                                    ->setParameter('so', $user->getSourceAgent()->getSourceOrgaAffectectation())
                                    ->setParameter('statut', 'attribuer')
                                    ->orderBy('v.vehImmat', 'ASC');
                },
                'property' => 'vehicule',
                'required' => false,
                'label' => 'Véhicule attribuée : '));
        }

        $builder->add('annuler', 'reset', array(
            'attr' => array(
                'class' => 'btn pull-right'),
        ));

        $builder->add('save', 'submit', array(
            'label' => 'Valider',
            'attr' => array(
                'class' => 'btn btn-primary pull-right',
            ),
        ));
    }

    public function getName()
    {
        return "User";
    }

}
