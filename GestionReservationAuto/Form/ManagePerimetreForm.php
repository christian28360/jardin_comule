<?php

namespace DTC\Modules\GestionReservationAuto\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form
 *
 * @author wug870
 */
class ManagePerimetreForm extends AbstractType
{

    /**
     * @var \Silex\Application
     */
    private $app;
    
    public function __construct(\Silex\Application $app)
    {
        $this->app = $app;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('perimetre', 'entity', array(
            'label' => 'Périmètre :',
            'class' => '\DTC\Modules\GestionReservationAuto\Entity\Perimetre',
            'em' => 'gestionReservationAuto',
            'property' => 'libelle',
            'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                return $er->createQueryBuilder('p')
                    ->orderBy('p.libelle', 'ASC');
            },
            'multiple' => false,
            'mapped' => false,
            'data' => $options['data'],
            'attr' => array(
                'class' => 'form-control',
                'data-url' => $this->app['url_generator']->generate('gestion-reservation-auto-manage-perimetre', array('perimetre' => 0)),
            ),
        ));
            
        $builder->add('filter', 'text', array(
            'label' => false,
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Rechercher un REGATE',
                'onkeypress' =>"refuserToucheEntree(event);"
            ),
            'required' => false,
            'mapped' => false,
        ));
        
        $builder->add('allow', 'button', array(
            'label' => '>>',
            'attr' => array(
                'class' => 'btn btn-default allow-deny-button',
            ),
        ));
        
        $builder->add('deny', 'button', array(
            'label' => '<<',
            'attr' => array(
                'class' => 'btn btn-default allow-deny-button',
            ),
        ));
        
        $builder->add('listeServices', 'collection', 
            array(
                'label'         => 'Service(s) :',
                'allow_add'     => true,
                'allow_delete'  => true,
                'by_reference'  => false,
                'type'          => 'text',
                'options'       => array(
                    'label' => false,
                    'required' => false,
                    'attr' => array(
                        'class' => 'hide',
                    ),
                ),
            )
        );
    }

    public function getName() {
        return "Perimetre";
    }
}