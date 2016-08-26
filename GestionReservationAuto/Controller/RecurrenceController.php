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
use DTC\Modules\GestionReservationAuto\Form\RecurrenceForm;

/**
 * Default controller of application
 *
 * @author wug870
 */
class RecurrenceController extends GlobalController
{

    protected function createAction(Request $request, Application $app)
    {
        if ($this->getGPAUser()->getVoitureAttribue() == null) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('You are not authorized');
        }

        $form = $this->app["form.factory"]->create(new RecurrenceForm(), null, array(
            'method' => $request->getMethod(),
        ));
        $form->handleRequest($request);
        
        if ($form->isValid()) {

            $DateDebut = $form->get('dateDebut')->getData();
            $DateFin = $form->get('dateFin')->getData();
            $recurrence = $form->get('recurrence')->getData();
            $nbRecurrence = $form->get('nbRecurrence')->getData();
            $depart = $form->get('depart')->getData();
            $destination = $form->get('destination')->getData();
            $distance = $form->get('distance')->getData();
            $motif = $form->get('motif')->getData();
            
            if ($DateFin < $DateDebut) {
                $this->app['session']->getFlashBag()->add(
                        'danger', 'La date de fin ne peu pas être inférieure à la date de début!'
                );
                return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/recurrence/create.html.twig', array("form" => $form->createView()));
            }
            
            for ($i = 1; $i <= $nbRecurrence; $i++) {
                $res = new Reservation();
                
                if ($recurrence == 'quotidien' AND $i > 1) {
                    $res->setDateDebut(clone $DateDebut->add(new \DateInterval('P1D')));
                    $res->setDateFin(clone $DateFin->add(new \DateInterval('P1D')));
                }elseif ($i == 1) {
                    $res->setDateDebut(clone $DateDebut);
                    $res->setDateFin(clone $DateFin);
                }elseif ($recurrence == 'hebdomadaire' AND $i > 1) {
                    $res->setDateDebut(clone $DateDebut->add(new \DateInterval('P7D')));
                    $res->setDateFin(clone $DateFin->add(new \DateInterval('P7D')));
                }elseif ($recurrence == 'mensuelle' AND $i > 1) {
                    $res->setDateDebut(clone $DateDebut->add(new \DateInterval('P1M')));
                    $res->setDateFin(clone $DateFin->add(new \DateInterval('P1M')));
                }
                $res->setDepart($depart);
                $res->setDestination($destination);
                $res->setDistance($distance);
                $res->setMotif($motif);
                $res->setUser($this->getGPAUser());
                $res->setVoiture($this->getGPAUser()->getVoitureAttribue());

                $rep = $this->app['orm.ems']['gestionReservationAuto']
                        ->getRepository("DTC\Modules\GestionReservationAuto\Entity\Reservation");
                
                if (!_empty($rep->findIfExiste($res))) {
                    $app['session']->getFlashBag()->add(
                            'danger', 'Réservation impossible entre le ' . $res->getDateDebut()->format("d/m/Y m:i") . ' et le ' . $res->getDateFin()->format("d/m/Y m:i") . '.'
                    );
                } else {
                    $this->app['orm.ems']['gestionReservationAuto']->persist($res);
                    $this->app['orm.ems']['gestionReservationAuto']->flush();
                    $this->app['session']->getFlashBag()->add(
                            'success', 'La réservation du ' . $res->getDateDebut()->format("d/m/Y m:i") . ' au ' . $res->getDateFin()->format("d/m/Y m:i") . ' a bien été enregistrées'
                    );
                    $lesRes[]= $res;
                }
            }
            if ($DateDebut->format('d/m/Y') != $DateFin->format('d/m/Y')) {
                //$lesRes = $rep->findByDateAndUser($dateCreation,$this->getGPAUser());
                return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/recurrence/bonRemisage.html.twig', array("lesRes" => $lesRes));
            } else {
                return $this->app->redirect(
                                $this->app['url_generator']->generate('gestion-reservation-auto-reservation-user')
                );
            }
        }
        return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/recurrence/create.html.twig', array("form" => $form->createView()));
    }

    protected function signatureAgentAction(Request $request, Application $app, $date)
    {
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Reservation");
        $lesRes = $repository->findByDateAndUser($date, $this->getGPAUser());
        //var_dump($lesRes);
        foreach ($lesRes as $res) {
            $res->setSignatureAgent($this->getGPAUser()->getNom() . ' ' . $this->getGPAUser()->getPrenom());
            //var_dump($res);
            $this->app['orm.ems']['gestionReservationAuto']->persist($res);
            $this->app['orm.ems']['gestionReservationAuto']->flush();
        }
        $this->app['session']->getFlashBag()->add(
                'success', 'Les bons ont été signés.'
        );
        return $this->app->redirect(
                        $this->app['url_generator']->generate('gestion-reservation-auto-reservation-user')
        );
    }

    
    
    protected function deleteAction(Request $request, Application $app, $date)
    {
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Reservation");
        $lesRes = $repository->findByDateAndUser($date, $this->getGPAUser());
        foreach ($lesRes as $res) {
            $res->setUser(null);
            $res->setVoiture(null);
            $this->app['orm.ems']['gestionReservationAuto']->remove($res);
            $this->app['orm.ems']['gestionReservationAuto']->flush();
        }
        $this->app['session']->getFlashBag()->add(
                'success', 'Les réservations ont été annulées.'
        );
        return $this->app->redirect(
                        $this->app['url_generator']->generate('gestion-reservation-auto-reservation-user')
        );
    }
}
