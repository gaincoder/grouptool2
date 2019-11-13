<?php

namespace EventBundle\Controller;

use App\Entity\Comment;
use EventBundle\Entity\RepeatingEvent;
use EventBundle\Entity\RepeatingEventVote;
use App\Form\CommentFormType;
use EventBundle\RepeatingEvent\RepeatingEventDeletedRepeatingEvent;
use EventBundle\RepeatingEvent\RepeatingEventEditedRepeatingEvent;
use EventBundle\Form\RepeatingEventFormType;
use EventBundle\RepeatingEvent\RepeatingEventAnsweredRepeatingEvent;
use EventBundle\RepeatingEvent\RepeatingEventCommentedRepeatingEvent;
use EventBundle\RepeatingEvent\RepeatingEventCreatedRepeatingEvent;
use EventBundle\RepeatingEvent\RepeatingEventSharedRepeatingEvent;
use EventBundle\Services\RepeatingEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class RepeatingEventController extends AbstractController
{

    /**
     * @Route("/repeatingEvent/create", name="repeatingEvent_create")
     * @IsGranted("ROLE_REPEATINGEVENT_CREATE")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        setlocale(LC_TIME, "de_DE");
        $repeatingEvent = new RepeatingEvent();
        $form = $this->createForm(RepeatingEventFormType::class, $repeatingEvent);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $repeatingEvent->createdBy = $this->getUser()->getUsername();
            $repeatingEvent->updatedBy = $this->getUser()->getUsername();
            $repeatingEvent->owner = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $em->persist($repeatingEvent);
            $em->flush();

            $service = new RepeatingEvents($this->getDoctrine()->getManager());
            $service->updateEvents($repeatingEvent);

            $this->addFlash('success', 'Serientermin wurde gespeichert!');
            return $this->redirectToRoute('event');
        }
        return $this->render('closed_area/RepeatingEvent/form.html.twig', ['form' => $form->createView(), 'page_title' => 'Serientermin hinzufügen']);
    }


    /**
     * @Route("/repeatingEvent/edit/{repeatingEvent}", name="repeatingEvent_edit")
     * @IsGranted("ROLE_REPEATINGEVENT_EDIT")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(RepeatingEvent $repeatingEvent, Request $request)
    {
        $form = $this->createForm(RepeatingEventFormType::class, $repeatingEvent);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repeatingEvent->updatedBy = $this->getUser()->getUsername();
            $em = $this->getDoctrine()->getManager();
            $em->persist($repeatingEvent);
            $em->flush();


            $service = new RepeatingEvents($this->getDoctrine()->getManager());
            $service->updateEvents($repeatingEvent);
            $this->addFlash('success', 'Serientermin wurde gespeichert!');
            return $this->redirectToRoute('event');
        }
        return $this->render('closed_area/RepeatingEvent/form.html.twig', ['form' => $form->createView(), 'page_title' => 'Serientermin bearbeiten']);
    }

    /**
     * @Route("/repeatingEvent/delete/{repeatingEvent}/{confirm}", name="repeatingEvent_delete",defaults={"confirm"=false})+-
     * @IsGranted("ROLE_REPEATINGEVENT_DELETE")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(RepeatingEvent $repeatingEvent, $confirm = false, Request $request)
    {
        if ($confirm == false) {
            return $this->render('closed_area/confirm.html.twig', ['type' => 'Serientermin']);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($repeatingEvent);
        $em->flush();
        $this->addFlash('success', 'Serientermin wurde gelöscht!');
        return $this->redirectToRoute('repeatingEvent');

    }

    /**
     * @Route("/repeatingEvent/view/{repeatingEvent}", name="repeatingEvent_view")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function view(RepeatingEvent $repeatingEvent, Request $request)
    {

        $futureEvents = $repeatingEvent->getFutureEvents(new \DateTime());

        return $this->render('closed_area/RepeatingEvent/view.html.twig', ['event' => $repeatingEvent,'events'=>$futureEvents]);
    }

    /**
     * @Route("/repeatingEvent/share/{repeatingEvent}", name="repeatingEvent_share")
     * @param repeatingEvent $repeatingEvent
     * @param Request $request
     */
    public function share(repeatingEvent $repeatingEvent, Request $request)
    {
        if ($repeatingEvent->permission == 0) {
            $this->get('repeatingEvent_dispatcher')->dispatch(new RepeatingEventSharedRepeatingEvent($repeatingEvent, $this->getUser()));
            $this->addFlash('success', 'Serientermin wurde geteilt!');
        } else {
            $this->addFlash('danger', 'Teilen nicht möglich! Sichtbarkeit ist eingeschränkt!');
        }
        return $this->redirectToRoute('repeatingEvent_view', ['repeatingEvent' => $repeatingEvent->id]);
    }




}
