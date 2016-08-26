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
use DTC\Modules\GestionReservationAuto\Form\ReservationForUserForm;

/**
 * Default controller of application
 *
 * @author wug870
 */
class GestionnaireReservationController extends GestionnaireController
{

    protected function serviceReservationAction(Request $request, Application $app)
    {
        $perimetres = $this->app['gra.sourceAgent']->getSourceOrgaAffectectation()->getPerimetres();
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Reservation");
        $res = $repository->findByPerimetre($perimetres);
        return $app['twig']->render('src/GestionReservationAuto/Resources/views/reservation/readByService.html.twig', array("lesReservations" => $res));
    }

    protected function signatureSuperieurAction(Request $request, Application $app, $id)
    {
        $signature = $this->getGPAUser()->getNom() . " " . $this->getGPAUser()->getPrenom();
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Reservation");
        $res = $repository->find($id);
        $res->setSignatureSuperieur($signature);
        $this->app['orm.ems']['gestionReservationAuto']->persist($res);
        $this->app['orm.ems']['gestionReservationAuto']->flush();
        $this->app['session']->getFlashBag()->add(
                'success', 'La signature a été enregistrée.'
        );
        return $this->app->redirect(
                        $this->app['url_generator']->generate('gestion-reservation-auto-reservation-service')
        );
    }

    protected function sendBonRemisageAction(Request $request, Application $app, $id)
    {
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Reservation");
        $res = $repository->find($id);
        $mailConfig = $this->app['dtc.config.manager']->getSettings('config', 'gpa_mail_configuration');

        $message = \Swift_Message::newInstance();
        $message->setSubject('GPA - Bon de remisage à domicile')
                ->setFrom($mailConfig['from'])
                ->setTo(array($this->app['gra.sourceAgent']->getCtcMessagerie()))
                ->setBody($app['twig']->render('src/GestionReservationAuto/Resources/views/reservation/mailBonRemisage.html.twig', array('res' => $res)
                        ), 'text/html');
        $this->app['mailer']->send($message);
        $this->app['session']->getFlashBag()->add(
                'success', 'Le mail a été envoyé.'
        );

        return $this->app->redirect(
                        $this->app['url_generator']->generate('gestion-reservation-auto-reservation-service')
        );
    }

    protected function readRegistreByServiceAction(Request $request, Application $app)
    {
        $service = $this->app['gra.sourceAgent']->getSourceOrgaAffectectation();
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Registre");
        $reg = $repository->findByService($service);
        return $app['twig']->render('src/GestionReservationAuto/Resources/views/registre/readByService.html.twig', array("lesRegistre" => $reg));
    }

    protected function signatureRegistreSuperieurAction(Request $request, Application $app, $id)
    {
        $signature = $this->getGPAUser()->getNom() . " " . $this->getGPAUser()->getPrenom();
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Registre");
        $reg = $repository->find($id);
        $reg->setSignatureSuperieur($signature);
        $this->app['orm.ems']['gestionReservationAuto']->persist($reg);
        $this->app['orm.ems']['gestionReservationAuto']->flush();
        $this->app['session']->getFlashBag()->add(
                'success', 'La signature a été enregistrée.'
        );
        return $this->app->redirect(
                        $this->app['url_generator']->generate('gestion-reservation-auto-registre-read-service')
        );
    }

    protected function sendRegistreAction(Request $request, Application $app, $id)
    {
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Registre");
        $res = $repository->find($id);
        $lesRes = $this->previewRegistreAction($request, $app, $res);
        $mailConfig = $this->app['dtc.config.manager']->getSettings('config', 'gpa_mail_configuration');

        $message = \Swift_Message::newInstance();
        $message->setSubject('GPA - Registre')
                ->setFrom($mailConfig['from'])
                ->setTo(array($this->app['gra.sourceAgent']->getCtcMessagerie()))
                ->setBody($app['twig']->render('src/GestionReservationAuto/Resources/views/registre/mailRegistre.html.twig', array('reg' => $res, 'lesRes' => $lesRes)
                        ), 'text/html');
        $this->app['mailer']->send($message);
        $this->app['session']->getFlashBag()->add(
                'success', 'Le mail a été envoyé.'
        );

        return $this->app->redirect(
                        $this->app['url_generator']->generate('gestion-reservation-auto-registre-read-service')
        );
    }

    private function previewRegistreAction(Request $request, Application $app, $reg)
    {
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Reservation");
        $lesRes = $repository->findByRegistre($reg);
        return $lesRes;
    }

    /**
     * Create reservation pour un utilisateur
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function createForUserAction(Request $request, Application $app)
    {
        $uneRes = new Reservation();
        $form = $this->app["form.factory"]
                ->create(new ReservationForUserForm($this->getGPAUser()), $uneRes, array(
            'method' => $request->getMethod(),
        ));
        
        $form->handleRequest($request);
        
        
        if ($form->isValid()) {
            $this->app['orm.ems']['gestionReservationAuto']->persist($uneRes);
            $this->app['orm.ems']['gestionReservationAuto']->flush();
            $this->app['session']->getFlashBag()->add(
                    'success', 'Les informations ont bien été enregistrées.'
            );
            return $this->app->redirect($this->app['url_generator']
                    ->generate('gestion-reservation-auto-reservation-service'));
        }
        
        //on passe la méthode createView() à la vue pour qu'elle l'affiche
        return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/reservation/createForUser.html.twig', array("form" => $form->createView()));
    }

    
    
    
    /**
     * Return a AJAX response
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */ 
    protected function findVoitureAndUserAction(Request $request, Application $app)
    { 
        $vRep = $this->getRepository(
                'gestionReservationAuto', 'DTC\Modules\GestionReservationAuto\Entity\Voiture'
        );
        $entity = new Reservation();

        $form = $this->app["form.factory"]
                ->create(new \DTC\Modules\GestionReservationAuto\Form\ReservationForUserForm($this->getGPAUser()), $entity, array(
            'method' => $request->getMethod(),
        ));
        $form->handleRequest($request);
        
        if (_empty($entity->getDateDebut()) || _empty($entity->getDateFin())) {
            $data['voitures'] = array();
            $data['users'] = array();
            $this->app->json($data);
        }
        foreach ($vRep->findNotInReserv($entity, $this->getGPAUser()) as $key => $voiture) {
            $data['voitures'][$key] = $voiture->toScalarArray();
            $data['voitures'][$key]['vehicule'] = $voiture->getVehicule()->toScalarArray();
        }
        
        $uRep = $this->getRepository(
                'gestionReservationAuto', 'DTC\Modules\GestionReservationAuto\Entity\User'
        );
        
        foreach ($uRep->findNotInReserv($entity, $this->getGPAUser()) as $key => $user) {
            $data['users'][$key] = $user->toScalarArray();
            $data['users'][$key]['sourceAgent'] = $user->getSourceAgent()->toScalarArray();
        }
        return $this->app->json($data);
    }

}
