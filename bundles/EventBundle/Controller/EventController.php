<?php

namespace EventBundle\Controller;

use App\Entity\Comment;
use EventBundle\Entity\Event;
use EventBundle\Entity\EventVote;
use App\Form\CommentFormType;
use EventBundle\Form\EventFormType;
use EventBundle\Event\EventAnsweredEvent;
use EventBundle\Event\EventCommentedEvent;
use EventBundle\Event\EventCreatedEvent;
use EventBundle\Event\EventSharedEvent;
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

        $events = $this->getDoctrine()->getRepository(Event::class)->findFuture($this->isGranted('ROLE_STAMMI'));
        return $this->render('closed_area/Event/index.html.twig', ['events' => $events]);
    }

    /**
     * @Route("/event/create", name="event_create")
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            $this->get('event_dispatcher')->dispatch(new EventCreatedEvent($event, $this->getUser()));
            $this->addFlash('success', 'Veranstaltung wurde gespeichert!');
            return $this->redirectToRoute('event');
        }
        return $this->render('closed_area/Event/form.html.twig', ['form' => $form->createView(), 'page_title' => 'Veranstaltung hinzufügen']);
    }


    /**
     * @Route("/event/edit/{event}", name="event_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Event $event, Request $request)
    {
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $event->updatedBy = $this->getUser()->getUsername();
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Veranstaltung wurde gespeichert!');
            return $this->redirectToRoute('event');
        }
        return $this->render('closed_area/Event/form.html.twig', ['form' => $form->createView(), 'page_title' => 'Veranstaltung bearbeiten']);
    }

    /**
     * @Route("/event/delete/{event}/{confirm}", name="event_delete",defaults={"confirm"=false})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Event $event, $confirm = false, Request $request)
    {
        if ($confirm == false) {
            return $this->render('closed_area/confirm.html.twig', ['type' => 'Veranstaltung']);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();
        $this->addFlash('success', 'Veranstaltung wurde gelöscht!');
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
        $commentform = $this->createForm(CommentFormType::class, $comment);
        $commentform->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($commentform->isSubmitted() && $commentform->isValid()) {

            $comment->user = $this->getUser();
            $em->persist($comment);
            $event->comments[] = $comment;
            $em->persist($event);
            $em->flush();


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
            $this->addFlash('success', 'Veranstaltung wurde geteilt!');
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
            $em->persist($answer);
            $em->flush();
            $this->addFlash('success', 'Antwort wurde gespeichert!');

            $this->get('event_dispatcher')->dispatch(new EventAnsweredEvent($event, $answer));

        }
        return $this->redirectToRoute('event_view', ['event' => $event->id]);
    }


}
