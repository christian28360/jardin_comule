<?php

namespace DTC\Modules\GestionReservationAuto\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Default controller of application
 *
 * @author wug870
 */
class CalendarController extends GlobalController
{

    protected function getCalendarAction(Request $request, Application $app)
    {
        return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/calendar/planning.html.twig');
    }

    protected function feedCalendarAction(Request $request, Application $app)
    {

        //trouver les réservations du perimetre
        $perimetre = $this->getGPAUser()->getPerimetre();
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Reservation");
        $res = $repository->findAllByPerimetre($perimetre);
        $x = array();

//        var_dump(count($res));exit();

        foreach ($res as $key => $r) {
            $class = "";
            $dDebut = $r->getDateDebut()->format("Y-m-d");
            $dFin = $r->getDateFin()->format("Y-m-d");
            if ($dFin > $dDebut) {
                $class = "event-important";
            } else {
                $class = "event-info";
            }
            $x[] = array(
                "id" => $r->getIdReservation(),
                "title" => $r->getVoiture()->getVehicule()->getVehImmat(),
                "url" => $this->app['url_generator']->generate('gestion-reservation-auto-calendar-find', array('id' => $r->getIdReservation())),
                "class" => $class,
                "start" => (strtotime($r->getDateDebut()->format("Y-m-d H:i:s")) * 1000),
                "end" => (strtotime($r->getDateFin()->format("Y-m-d H:i:s")) * 1000)
            );
        }
        $array = array("success" => 1,
            "result" => $x);
        return $this->app->json($array);
    }

    protected function findResAction(Request $request, Application $app, $id)
    {
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Reservation");
        $res = $repository->find($id);
        return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/calendar/detail.html.twig', array("res" => $res));
    }

}
