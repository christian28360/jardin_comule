<?php

namespace DTC\Modules\GestionReservationAuto\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use DTC\Modules\GestionReservationAuto\Entity\Reservation;
use DTC\Modules\GestionReservationAuto\Form\ReservationForm;

/**
 * Default controller of application
 *
 * @author wug870
 */
class AdminReservationController extends AdministrationController
{
    /**
     * Readl all reservation
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     * Page qui affiche toute les réservations qui existe
     */
    protected function homeAction(Request $request, Application $app)
    {
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Reservation");
        $lesReservations = $repository->findAll();
        return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/reservation/readall.html.twig', array("lesReservations"=>$lesReservations));
    }
    /**
     * signature superieur
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     * Signe une réservation en tant que supérieur
     */
    protected function signatureSuperieurAction(Request $request, Application $app, $id)
    {
        $signature = $this->getGPAUser()->getNom()." ".$this->getGPAUser()->getPrenom();
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Reservation");
        $res = $repository->find($id);
        $res->setSignatureSuperieur($signature);
        $this->app['orm.ems']['gestionReservationAuto']->persist($res);
        $this->app['orm.ems']['gestionReservationAuto']->flush();
        $this->app['session']->getFlashBag()->add(
            'success', 
            'La signature a été enregistrée.'
        );
        return $this->app->redirect(
            $this->app['url_generator']->generate('gestion-reservation-auto-reservation-service')
        );
    }
}
