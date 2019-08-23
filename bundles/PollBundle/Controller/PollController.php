<?php

namespace PollBundle\Controller;

use App\Entity\Comment;
use PollBundle\Entity\Poll;
use PollBundle\Entity\UserVote;
use App\Form\CommentFormType;
use PollBundle\Entity\PollAnswer;
use PollBundle\Form\PollFormType;
use PollBundle\Form\PollSimpleFormType;
use PollBundle\Event\PollAnsweredEvent;
use PollBundle\Event\PollCommentedEvent;
use PollBundle\Event\PollCreatedEvent;
use PollBundle\Event\PollSharedEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PollController extends AbstractController
{
    /**
     * @Route("/poll", name="poll")
     */
    public function indexAction(Request $request)
    {
        if ($this->isGranted('ROLE_STAMMI')) {
            $polls = $this->getDoctrine()->getRepository(Poll::class)->findAllOrdered(1);
        } else {
            $polls = $this->getDoctrine()->getRepository(Poll::class)->findAllOrdered(0);
        }
        return $this->render('closed_area/Poll/index.html.twig', ['polls' => $polls]);
    }

    /**
     * @Route("/Poll/create", name="poll_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $poll = new Poll();
        $form = $this->createForm(PollFormType::class, $poll);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $poll->createdBy = $this->getUser()->getUsername();
            $poll->updatedBy = $this->getUser()->getUsername();
            $em = $this->getDoctrine()->getManager();
            $poll->owner = $this->getUser();
            $em->persist($poll);
            $em->flush();
            $this->get('event_dispatcher')->dispatch(new PollCreatedEvent($poll, $this->getUser()));


            $this->addFlash('success', 'Umfrage wurde gespeichert!');
            return $this->redirectToRoute('poll_view', ['poll' => $poll->id]);
        }
        return $this->render('closed_area/Poll/form.html.twig', ['form' => $form->createView(), 'page_title' => 'Umfrage hinzufügen']);
    }


    /**
     * @Route("/Poll/edit/{poll}", name="poll_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Poll $poll, Request $request)
    {
        if ($poll->owner == $this->getUser() || $this->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(PollFormType::class, $poll);
            $tpl = 'closed_area/Poll/form.html.twig';
        } else {
            $form = $this->createForm(PollSimpleFormType::class, $poll);
            $tpl = 'closed_area/Poll/form_simple.html.twig';
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $poll->updatedBy = $this->getUser()->getUsername();
            $em = $this->getDoctrine()->getManager();
            $em->persist($poll);
            $em->flush();
            $this->addFlash('success', 'Umfrage wurde gespeichert!');
            return $this->redirectToRoute('poll_view', ['poll' => $poll->id]);
        }
        return $this->render($tpl, ['form' => $form->createView(), 'page_title' => 'Umfrage bearbeiten', 'poll' => $poll]);
    }

    /**
     * @Route("/Poll/delete/{poll}/{confirm}", name="poll_delete",defaults={"confirm"=false})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Poll $poll, $confirm = false, Request $request)
    {
        if ($confirm == false) {
            return $this->render('closed_area/confirm.html.twig', ['type' => 'Umfrage']);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($poll);
        $em->flush();
        $this->addFlash('success', 'Umfrage wurde gelöscht!');
        return $this->redirectToRoute('poll');

    }

    /**
     * @Route("/Poll/view/{poll}", name="poll_view")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function view(Poll $poll, Request $request)
    {

        $comment = new Comment();
        $commentform = $this->createForm(CommentFormType::class, $comment);
        $commentform->handleRequest($request);
        if ($commentform->isSubmitted() && $commentform->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $comment->user = $this->getUser();
            $em->persist($comment);
            $poll->comments[] = $comment;
            $em->persist($poll);
            $em->flush();

            $this->get('event_dispatcher')->dispatch(new PollCommentedEvent($poll, $this->getUser()));


            return $this->redirectToRoute('poll_view', ['poll' => $poll->id]);
        }

        $answerRepo = $this->getDoctrine()->getRepository(PollAnswer::class);
        $answers = $answerRepo->getOrderedForPoll($poll);

        return $this->render('closed_area/Poll/view.html.twig', ['poll' => $poll, 'commentform' => $commentform->createView(), 'answers' => $answers]);
    }

    /**
     * @Route("/Poll/save/{poll}", name="poll_save")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function save(Poll $poll, Request $request)
    {
        if ($poll->isOpen() && $request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $pollAnswerRepo = $em->getRepository(PollAnswer::class);
            foreach ($poll->getAnswers() as $answer) {
                $userVote = $answer->getVoteForUser($this->getUser());
                if ($userVote instanceof UserVote) {
                    $em->remove($userVote);
                }
            }
            $em->flush();
            $answers = array();
            if (isset($_POST['answer']) && is_array($_POST['answer'])) {
                $answers = $_POST['answer'];
            }
            foreach ($answers as $answerId => $vote) {
                $userVote = new UserVote();
                $userVote->user = $this->getUser();
                $userVote->vote = 1;
                $userVote->answer = $pollAnswerRepo->find($vote);
                $em->persist($userVote);
            }
            $em->flush();
            $this->addFlash('success', 'Antworten wurden gespeichert!');

            $this->get('event_dispatcher')->dispatch(new PollAnsweredEvent($poll, $this->getUser()));

        }
        return $this->redirectToRoute('poll_view', ['poll' => $poll->id]);

    }


    /**
     * @Route("/Poll/toggleStatus/{poll}", name="poll_toggle_status")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toggleStatus(Poll $poll, Request $request)
    {
        $poll->closed = !$poll->closed;
        $em = $this->getDoctrine()->getManager();
        $em->persist($poll);
        $em->flush();
        return $this->redirectToRoute('poll_view', ['poll' => $poll->id]);
    }

    /**
     * @Route("/Poll/share/{poll}", name="poll_share")
     * @param Poll $poll
     * @param Request $request
     */
    public function share(Poll $poll, Request $request)
    {
        if ($poll->permission == 0) {

            $this->get('event_dispatcher')->dispatch(new PollSharedEvent($poll, $this->getUser()));

            $this->addFlash('success', 'Umfrage wurde geteilt!');
        } else {
            $this->addFlash('danger', 'Teilen nicht möglich! Sichtbarkeit ist eingeschränkt!');
        }
        return $this->redirectToRoute('poll_view', ['poll' => $poll->id]);
    }
}
