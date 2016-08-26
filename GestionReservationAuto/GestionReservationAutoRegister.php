<?php

namespace DTC\Modules\GestionReservationAuto;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\ServiceProviderInterface;
use Doctrine\ORM\Events;

/**
 * Description of SimulateurBudgetRegister
 *
 * @author glr735
 */
class GestionReservationAutoRegister implements ServiceProviderInterface
{
    static public $APPLICATION_SLUG = 'GRA';

    public function boot(\Silex\Application $app)
    {
        
    }

    public function register(\Silex\Application $app)
    {
        $app['dtc.user.ntlm.refresh'];
        
        $em = $app['orm.ems']['gestionReservationAuto'];
        
        $em->getConfiguration()->addFilter('soft-deleteable', 'Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter');
        $em->getFilters()->enable('soft-deleteable');
        
        $app['gra.sourceAgent'] = $app->share(function () use ($app, $em) {
            return $em->getRepository('DTC\Modules\GestionReservationAuto\Entity\GestionRh\SourceAgent')
                    ->findOneByAgeCodeDt($app['dtc.user.ntlm']->getEntity()->getCodeRh());
        });
        
        // Si l'agent n'est pas reconnu ou s'il n'a pas de hierarchique alors
        // l'accès à l'application est refusé
        if (is_null($app['gra.sourceAgent']) || is_null($app['gra.sourceAgent']->getResponsableHierarchique())) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Vous n\'êtes pas habilité à saisir des réservations');
        }
    }

}
