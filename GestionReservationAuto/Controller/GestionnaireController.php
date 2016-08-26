<?php

namespace DTC\Modules\GestionReservationAuto\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use DTC\Modules\GestionReservationAuto\Entity;

/**
 * Default controller of application
 *
 * @author glr735
 */
class GestionnaireController extends GlobalController
{
    
    public function __call($func, $args)
    {
        $controllerStatement = parent::__call($func, $args);
        
        if ($this->getGPAUser()->getGrade() != 'gestionnaire') {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('You are not authorized');
        }
        
        return $controllerStatement;
    }
    
}
