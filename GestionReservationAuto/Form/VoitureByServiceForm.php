<?php

namespace DTC\Modules\GestionReservationAuto\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form
 *
 * @author wug870
 */
class VoitureByServiceForm extends AbstractType
{

    /**
     * @var \DTC\Modules\GestionReservationAuto\Entity\Organization\SourceOrga
     */
    private $so;

    public function __construct( $so, $vehicules, $mode)
    {
        $this->so = $so;
        $this->vehicules = $vehicules;
        $this->mode = $mode;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $vehicule = $this->vehicules;
        $so = $this->so;
        $mode = $this->mode;
        $builder->add('commentaire', 'text', array(
            'label' => 'Commentaire :'
        ));

        $builder->add('statut', 'choice', array(
            'choices' => array('disponible' => 'Disponible', 'indisponible' => 'Indisponible', 'attribuer' => 'Attribuer'),
            'preferred_choices' => array('disponnible'),
        ));

        $builder->add('vehicule', 'entity', array(
            'label' => 'Véhicule :',
            'class' => '\DTC\Modules\GestionReservationAuto\Entity\Vehicule\Vehicule',
            'em' => 'gestionReservationAuto',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use($vehicule, $so, $mode) {
                if ($vehicule != null AND $mode == "create") {
                    return $er->createQueryBuilder('p')
                                    ->where('p.locSourceOrga IN (:so)')
                                    ->andWhere('p.vehCodeParc NOT IN (:voitures)')
                                    ->setParameter('voitures', $vehicule)
                                    ->setParameter('so', $so)
                                    ->orderBy('p.vehImmat', 'ASC');
                } else {
                    return $er->createQueryBuilder('p')
                                    ->where('p.locSourceOrga IN (:so)')
                                    ->setParameter('so', $so)
                                    ->orderBy('p.vehImmat', 'ASC');
                }
            },
            'multiple' => false,
            'attr' => array(
                'class' => 'form-control',
            ),
        ));
    }

    public function getName()
    {
        return "VoitureForm";
    }

}
