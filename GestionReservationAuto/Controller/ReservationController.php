<?php

namespace DTC\Modules\GestionReservationAuto\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use DTC\Modules\GestionReservationAuto\Entity\Reservation;
use DTC\Modules\GestionReservationAuto\Form\ReservationForm;
use DTC\Modules\GestionReservationAuto\Entity\Registre;
use DTC\Modules\GestionReservationAuto\Form\RegistreForm;

/**
 * Default controller of application
 *
 * @author wug870 & EZS824
 */
class ReservationController extends GlobalController
{

    /**
     * Create reservation
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function createAction(Request $request, Application $app)
    {
        $user = $this->getGPAUser();

        $uneRes = new Reservation($user);

        $form = $this->app["form.factory"]
                ->create(new ReservationForm($user), $uneRes, array(
            'method' => $request->getMethod(),
        ));

        $rep = $this->app['orm.ems']['gestionReservationAuto']
                ->getRepository("DTC\Modules\GestionReservationAuto\Entity\Reservation");
        $form->handleRequest($request);
        $uneRes->setUser($this->getGPAUser());
        //var_dump($rep->findIfExiste($uneRes));exit();

        if ($form->isValid()) {
            if (!_empty($rep->findIfExiste($uneRes))) {
                $app['session']->getFlashBag()->add(
                        'danger', 'Vous avez déjà une réservation dans ces dates ou la voiture vient d\'être réservée.'
                );
                return $this->app->redirect(
                                $this->app['url_generator']->generate('gestion-reservation-auto-reservation-create')
                );
            }
            $this->app['orm.ems']['gestionReservationAuto']->persist($uneRes);
            
            $this->app['orm.ems']['gestionReservationAuto']->flush();
            $this->app['session']->getFlashBag()->add(
                    'success', 'Les informations ont bien été enregistrées.'
            );

            if (date_format($uneRes->getDateDebut(), 'Y-m-d') != date_format($uneRes->getDateFin(), 'Y-m-d')) {
                return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/reservation/bonRemisage.html.twig', array("res" => $uneRes));
            } else {
                return $this->app->redirect($this->app['url_generator']
                                        ->generate('gestion-reservation-auto-calendar'));
            }
        }

        return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/reservation/create.html.twig', array("form" => $form->createView()));
    }

    /**
     * Update reservation
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function updateAction(Request $request, Application $app)
    {

        // get curent résa
        $res = $this->getCurrent($this->request->get('id'));

        // voir si la résa est modifiable
        //$form = null;
        $toDay = new \DateTime();
        if ($res->getDateDebut() >= $toDay) {

            $form = $this->app["form.factory"]->create(
                    new ReservationForm($this->getGPAUser()), $res, array(
                'method' => $this->request->getMethod(),
                    )
            );
            return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/reservation/create.html.twig', array("form" => $form->createView()));
//            $form = $this->app["form.factory"]
//                    ->create(new ReservationForm($this->getGPAUser()), $uneRes, array(
//                'method' => $request->getMethod(),
//            ));
        } else {
            $this->app['session']->getFlashBag()->add(
                    'danger', 'On ne peut modifier une résevation en cours ou passée'
            );
        }

        return $this->app->redirect(
                        $this->app['url_generator']->generate('gestion-reservation-auto-reservation-user')
        );
    }

    /**
     * Delete reservation
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function deleteAction(Request $request, Application $app)
    {

        $res = $this->app['orm.ems']['gestionReservationAuto']
                ->getRepository("DTC\Modules\GestionReservationAuto\Entity\Reservation")
                ->findOneByIdReservation($request->get('id'));

        // voir si la résa est deletable
        $toDay = new \DateTime();
        if (!$res->getDateDebut() <= $toDay || $res->getDateFin() <= $toDay) {

            $this->app['orm.ems']['gestionReservationAuto']->remove($res);
            $this->app['orm.ems']['gestionReservationAuto']->flush();

            $this->app['session']->getFlashBag()->add(
                    'success', 'La réservation a bien été annulée'
            );
        } else {
            $this->app['session']->getFlashBag()->add(
                    'danger', 'On ne peut annuler une résevation en cours ou passée'
            );
        }

        return $this->app->redirect(
                        $this->app['url_generator']->generate('gestion-reservation-auto-reservation-user')
        );
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application $app
     */
    protected function findVoitureAction(Request $request, Application $app)
    {
        $repository = $this->getRepository(
                'gestionReservationAuto', 'DTC\Modules\GestionReservationAuto\Entity\Voiture'
        );

        $data = array(
            'voitures' => array(),
            'users' => array(),
        );

        $entity = new Reservation($this->getGPAUser());
        $form = $this->app["form.factory"]->create(
                new \DTC\Modules\GestionReservationAuto\Form\ReservationForUserForm($this->getGPAUser()), $entity, array(
            'method' => $request->getMethod(),
        ));
        $form->handleRequest($request);
        $vehDispo = $repository->findNotInReserv($entity, $this->getGPAUser());
        //if ($form->isValid() && !_empty($vehDispo = $repository->findNotInReserv($entity, $this->getGPAUser()))) {
        if (!_empty($vehDispo)) {
            foreach ($vehDispo as $key => $voiture) {
                $data['voitures'][$key] = $voiture->toScalarArray();
                $data['voitures'][$key]['vehicule'] = $voiture->getVehicule()->toScalarArray();
            }
        }

        return $this->app->json($data);
    }

    protected function signatureAgentAction(Request $request, Application $app, $id)
    {
        //Trouver la réservation en cause
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Reservation");
        $res = $repository->find($id);
        //Signer la reservation avec les infos de l'utilisateur

        $signature = $this->app['gra.sourceAgent']->getAgePrenomUsuel() . " " . $this->app['gra.sourceAgent']->getAgeNomUsuel();
        $res->setSignatureAgent($signature);
        $this->app['orm.ems']['gestionReservationAuto']->persist($res);
        $this->app['orm.ems']['gestionReservationAuto']->flush();
        $this->app['session']->getFlashBag()->add(
                'success', 'La signature a été enregistrée'
        );
        //Envoyer une alerte mail au gestionnaire de service.
        $mailConfig = $this->app['dtc.config.manager']->getSettings('config', 'gpa_mail_configuration');
        $array = array($this->getGPAUser()->getMailSuperieur());
        $message = \Swift_Message::newInstance();
        $message->setSubject('GRA - Alerte bon de remisage à domicile')
                ->setFrom($mailConfig['from'])
                ->setTo($array)
                ->setBody($app['twig']->render('src/GestionReservationAuto/Resources/views/reservation/mailAlerteBonRemisage.html.twig', array('res' => $res)
                        ), 'text/html');
        $this->app['mailer']->send($message);
        //var_dump($mails);

        return $this->app->redirect(
                        $this->app['url_generator']->generate('gestion-reservation-auto-reservation-user')
        );
    }

    protected function myReservationAction(Request $request, Application $app)
    {
        $user = $this->getGPAUser();
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Reservation");
        $res = $repository->findByUser($user);
        return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/reservation/readByUser.html.twig', array("lesReservations" => $res));
    }

    /*
     * Envois un mail avec les infos du bon de remisage à domicile
     */

    protected function sendBonRemisageAction(Request $request, Application $app, $id)
    {
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Reservation");
        $res = $repository->find($id);
        $mailConfig = $this->app['dtc.config.manager']->getSettings('config', 'gpa_mail_configuration');

        $message = \Swift_Message::newInstance();
        $message->setSubject('GRA - Bon de remisage à domicile')
                ->setFrom($mailConfig['from'])
                ->setTo(array($this->app['gra.sourceAgent']->getCtcMessagerie()))
                ->setBody($app['twig']->render('src/GestionReservationAuto/Resources/views/reservation/mailBonRemisage.html.twig', array('res' => $res)
                        ), 'text/html');
        $this->app['mailer']->send($message);
        $this->app['session']->getFlashBag()->add(
                'success', 'Le mail a été envoyé'
        );

        return $this->app->redirect(
                        $this->app['url_generator']->generate('gestion-reservation-auto-reservation-user')
        );
    }

    protected function registreAction(Request $request, Application $app)
    {
        //lister les registre de l'utilisateur
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Registre");
        $lesReg = $repository->findByUser($this->getGPAUser());

        $reg = new Registre();
        $form = $this->app["form.factory"]->create(new RegistreForm(), $reg, array(
            'method' => $request->getMethod(),
        ));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $reg->setDateCreation(new \DateTime('now'));
            $reg->setUser($this->getGPAUser());
            $this->app['orm.ems']['gestionReservationAuto']->persist($reg);
            $this->app['orm.ems']['gestionReservationAuto']->flush();
            $this->app['session']->getFlashBag()->add(
                    'success', 'Les informations ont bien été enregistrées'
            );
            //var_dump($reg);
            $lesRes = $this->previewRegistreAction($request, $app, $reg);
            return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/registre/preview.html.twig', array("lesRes" => $lesRes, "reg" => $reg));
        }
        return $this->app['twig']->render('src/GestionReservationAuto/Resources/views/registre/create.html.twig', array("form" => $form->createView(), "lesReg" => $lesReg));
    }

    private function previewRegistreAction(Request $request, Application $app, $reg)
    {
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Reservation");
        $lesRes = $repository->findByRegistre($reg);
        return $lesRes;
    }

    protected function signatureAgentRegistreAction(Request $request, Application $app, $id)
    {
        //Trouver la réservation en cause
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Registre");
        $reg = $repository->find($id);
        //Signer la reservation avec les infos de l'utilisateur

        $signature = $this->getGPAUser()->getNom() . " " . $this->getGPAUser()->getPrenom();
        $reg->setSignatureAgent($signature);
        $this->app['orm.ems']['gestionReservationAuto']->persist($reg);
        $this->app['orm.ems']['gestionReservationAuto']->flush();
        $this->app['session']->getFlashBag()->add(
                'success', 'La signature a été enregistrée'
        );
        //Envoyer une alerte mail au gestionnaire de service.
        $mailConfig = $this->app['dtc.config.manager']->getSettings('config', 'gpa_mail_configuration');
        $array = array($this->getGPAUser()->getMailSuperieur());
        $message = \Swift_Message::newInstance();
        $message->setSubject('GRA - Alerte registre des réservations')
                ->setFrom($mailConfig['from'])
                ->setTo($array)
                ->setBody($app['twig']->render('src/GestionReservationAuto/Resources/views/reservation/mailAlerteRegistre.html.twig', array('reg' => $reg)
                        ), 'text/html');
        $this->app['mailer']->send($message);
        //var_dump($array);
        return $this->app->redirect(
                        $this->app['url_generator']->generate('gestion-reservation-auto-registre-create')
        );
    }

    protected function deleteRegistreAction(Request $request, Application $app, $id)
    {
        $reg = $this->app['orm.ems']['gestionReservationAuto']
                ->getRepository("DTC\Modules\GestionReservationAuto\Entity\Registre")
                ->findOneByIdRegistre($request->get('id'));
        // passage de l'attribue à null sinon impossible de supprimer avec la contrainte en base
        $reg->setUser(null);
        $this->app['orm.ems']['gestionReservationAuto']->remove($reg);
        $this->app['orm.ems']['gestionReservationAuto']->flush();

        $this->app['session']->getFlashBag()->add(
                'success', 'Le registre a bien été annulé'
        );

        return $this->app->redirect(
                        $this->app['url_generator']->generate('gestion-reservation-auto-registre-create')
        );
    }

    protected function sendRegistreAction(Request $request, Application $app, $id)
    {
        $repository = $this->app['orm.ems']['gestionReservationAuto']->getRepository("DTC\Modules\GestionReservationAuto\Entity\Registre");
        $res = $repository->find($id);
        $lesRes = $this->previewRegistreAction($request, $app, $res);
        $mailConfig = $this->app['dtc.config.manager']->getSettings('config', 'gpa_mail_configuration');

        $message = \Swift_Message::newInstance();
        $message->setSubject('GRA - Registre')
                ->setFrom($mailConfig['from'])
                ->setTo(array($this->app['gra.sourceAgent']->getCtcMessagerie()))
                ->setBody($app['twig']->render('src/GestionReservationAuto/Resources/views/registre/mailRegistre.html.twig', array('reg' => $res, 'lesRes' => $lesRes)
                        ), 'text/html');
        $this->app['mailer']->send($message);
        $this->app['session']->getFlashBag()->add(
                'success', 'Le mail a été envoyé'
        );
        return $this->app->redirect(
                        $this->app['url_generator']->generate('gestion-reservation-auto-registre-create')
        );
    }

    private function getCurrent($id = NULL)
    {
        $em = $this->app['orm.ems']['gestionReservationAuto']
                        ->getRepository("DTC\Modules\GestionReservationAuto\Entity\Reservation")->find($id);

        return $em;
    }

}
