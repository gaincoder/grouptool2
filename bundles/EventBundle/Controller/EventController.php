<?php

namespace EventBundle\Controller;

use App\Entity\Comment;
use EventBundle\Entity\Event;
use EventBundle\Entity\EventVote;
use App\Form\CommentFormType;
use EventBundle\Entity\RepeatingEvent;
use EventBundle\Event\EventDeletedEvent;
use EventBundle\Event\EventEditedEvent;
use EventBundle\Event\EventNotificationEvent;
use EventBundle\Form\EventFormType;
use EventBundle\Event\EventAnsweredEvent;
use EventBundle\Event\EventCommentedEvent;
use EventBundle\Event\EventCreatedEvent;
use EventBundle\Event\EventSharedEvent;
use EventBundle\Form\SimpleEventFormType;
use EventBundle\Services\RepeatingEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function indexAction(Request $request)
    {

        $events = $this->getDoctrine()->getRepository(Event::class)->findFuture($this->getUser()->getGroups());
        $repeatingEvents = $this->getDoctrine()->getRepository(RepeatingEvent::class)->findForGroups($this->getUser()->getGroups());
        return $this->render('closed_area/Event/index.html.twig', ['events' => $events,'repeatingEvents'=>$repeatingEvents]);
    }

    /**
     * @Route("/event/create", name="event_create")
     * @IsGranted("ROLE_EVENT_CREATE")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        setlocale(LC_TIME, "de_DE");
        $event = new Event();
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $event->createdBy = $this->getUser()->getUsername();
            $event->updatedBy = $this->getUser()->getUsername();
            $event->owner = $this->getUser();
            $event->notifications[] = $this->getUser();

            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            $this->get('event_dispatcher')->dispatch(new EventCreatedEvent($event, $this->getUser()));
            $this->addFlash('success', 'Aktivität wurde gespeichert!');
            return $this->redirectToRoute('event');
        }
        return $this->render('closed_area/Event/form.html.twig', ['form' => $form->createView(), 'page_title' => 'Aktivität hinzufügen']);
    }


    /**
     * @Route("/event/edit/{event}", name="event_edit")
     * @IsGranted("ROLE_EVENT_EDIT")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Event $event, Request $request)
    {
        $oldEvent = clone $event;
        if($event->repeatingEvent instanceof RepeatingEvent){
            $form = $this->createForm(SimpleEventFormType::class, $event);
        }else{
            $form = $this->createForm(EventFormType::class, $event);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $event->updatedBy = $this->getUser()->getUsername();
            if($oldEvent->date != $event->date || $oldEvent->location != $event->location){
                $event->manualChanged = true;
            }
            if($event->userBecomesNotification($this->getUser()) === false){
                $event->notifications[] = $this->getUser();
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Aktivität wurde gespeichert!');
            $this->get('event_dispatcher')->dispatch(new EventEditedEvent($event, $this->getUser()));
            return $this->redirectToRoute('event');
        }
        return $this->render('closed_area/Event/form.html.twig', ['form' => $form->createView(), 'page_title' => 'Aktivität bearbeiten']);
    }

    /**
     * @Route("/event/delete/{event}/{confirm}", name="event_delete",defaults={"confirm"=false})
     * @IsGranted("ROLE_EVENT_DELETE")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Event $event, $confirm = false, Request $request)
    {
        if ($confirm == false) {
            return $this->render('closed_area/confirm.html.twig', ['type' => 'Aktivität']);
        }
        $em = $this->getDoctrine()->getManager();
        if($event->repeatingEvent instanceof RepeatingEvent){
            $event->archived = true;
            $em->persist($event);
        }else{
            $em->remove($event);
        }
        $em->flush();
        $this->addFlash('success', 'Aktivität wurde gelöscht!');
        $this->get('event_dispatcher')->dispatch(new EventDeletedEvent($event, $this->getUser()));
        return $this->redirectToRoute('event');

    }

    /**
     * @Route("/event/view/{event}", name="event_view")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function view(Event $event, Request $request)
    {
        $comment = new Comment();
        $commentform = $this->createForm(CommentFormType::class, $comment,array('allow_extra_fields' =>true));
        $commentform->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($commentform->isSubmitted() && $commentform->isValid()) {

            $comment->user = $this->getUser();
            $em->persist($comment);
            $event->comments[] = $comment;
            $em->persist($event);
            $em->flush();
            $extraData = $commentform->getExtraData();
            if(isset($extraData['sendnotifiction']) && $extraData['sendnotifiction'] == 1){
                $this->get('event_dispatcher')->dispatch(new EventNotificationEvent($event, $this->getUser()));
            }

            $this->get('event_dispatcher')->dispatch(new EventCommentedEvent($event, $this->getUser()));


            return $this->redirectToRoute('event_view', ['event' => $event->id]);
        }
        $voteRepo = $em->getRepository(EventVote::class);
        if (!($answer = $voteRepo->getForEventAndUser($event, $this->getUser()))) {
            $current = 0;
        } else {
            $current = $answer->vote;
        }
        return $this->render('closed_area/Event/view.html.twig', ['event' => $event, 'current' => $current, 'commentform' => $commentform->createView(), 'voteRepo' => $voteRepo]);
    }

    /**
     * @Route("/event/share/{event}", name="event_share")
     * @param event $event
     * @param Request $request
     */
    public function share(event $event, Request $request)
    {
        if ($event->permission == 0) {
            $this->get('event_dispatcher')->dispatch(new EventSharedEvent($event, $this->getUser()));
            $this->addFlash('success', 'Aktivität wurde geteilt!');
        } else {
            $this->addFlash('danger', 'Teilen nicht möglich! Sichtbarkeit ist eingeschränkt!');
        }
        return $this->redirectToRoute('event_view', ['event' => $event->id]);
    }


    /**
     * @Route("/event/save/{event}", name="event_save")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function save(Event $event, Request $request)
    {
        if ($request->isMethod('POST') && isset($_POST['answer'])) {
            $em = $this->getDoctrine()->getManager();
            $voteRepo = $em->getRepository(EventVote::class);
            if (!($answer = $voteRepo->getForEventAndUser($event, $this->getUser()))) {
                $answer = new EventVote();
                $answer->user = $this->getUser();
                $answer->event = $event;
            }
            $answer->vote = $_POST['answer'];
            if($answer->vote == 1 or $answer->vote == 3){
                if($event->userBecomesNotification($this->getUser()) === false){
                    $event->notifications[] = $this->getUser();
                    $em->persist($event);
                }
            }
            $em->persist($answer);
            $em->flush();
            $this->addFlash('success', 'Antwort wurde gespeichert!');

            $this->get('event_dispatcher')->dispatch(new EventAnsweredEvent($event, $answer));

        }
        return $this->redirectToRoute('event_view', ['event' => $event->id]);
    }

    /**
     * @Route("/event/reset/{event}", name="event_reset")
     * @IsGranted("ROLE_REPEATINGEVENT_EDIT")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reset(Event $event, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($event->repeatingEvent instanceof RepeatingEvent) {
            $event->archived = false;
            $event->manualChanged = false;
            $em->persist($event);
            $em->flush();

            $service = new RepeatingEvents($this->getDoctrine()->getManager());
            $service->updateEvents($event->repeatingEvent);
            $this->addFlash('success', 'Aktivität wurde zurückgesetzt!');
        }
        return $this->redirectToRoute('event');

    }

    /**
     * @Route("/event/toggleNotification/{event}", name="event_toggle_notification")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toggleNotification(Event $event, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $origEvent = $event;
        $repating = false;
        if($event->repeatingEvent instanceof RepeatingEvent) {
            $event = $event->repeatingEvent;
            $repating = true;
        }
        if(($key = $event->userBecomesNotification($this->getUser())) !== false){
            unset($event->notifications[$key]);
        }else{
            $event->notifications[] = $this->getUser();
        }
        $em->persist($event);
        $em->flush();
        if($repating) {
            $service = new RepeatingEvents($this->getDoctrine()->getManager());
            $service->updateEvents($event);
        }
        return $this->redirectToRoute('event_view',['event'=>$origEvent->id]);

    }

}
