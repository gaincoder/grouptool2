<?php

namespace BirthdayBundle\Controller;

use BirthdayBundle\Entity\Birthday;
use BirthdayBundle\Form\BirthdayFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BirthdayController extends AbstractController
{
    /**
     * @Route("/birthday", name="birthday")
     * @return Response
     */
    public function indexAction()
    {
        $pastBirthdays = $this->getDoctrine()->getRepository(Birthday::class)->findAllPastOrderedByDay();
        $futureBirthdays = $this->getDoctrine()->getRepository(Birthday::class)->findAllFutureOrderedByDay();
        return $this->render('closed_area/Birthday/index.html.twig', ['pastBirthdays' => $pastBirthdays, 'futureBirthdays' => $futureBirthdays]);
    }

    /**
     * @Route("/birthday/create", name="birthday_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        setlocale(LC_TIME, "de_DE");
        $birthday = new Birthday();
        $form = $this->createForm(BirthdayFormType::class, $birthday);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($birthday);
            $em->flush();
            $this->addFlash('success', 'Geburtstag wurde gespeichert!');
            return $this->redirectToRoute('birthday');
        }
        return $this->render('closed_area/Birthday/form.html.twig', ['form' => $form->createView(), 'page_title' => 'Geburtstag hinzufügen']);
    }


    /**
     * @Route("/birthday/edit/{birthday}", name="birthday_edit")
     * @param Birthday $birthday
     * @param Request $request
     * @return Response
     */
    public function edit(Birthday $birthday, Request $request)
    {
        $form = $this->createForm(BirthdayFormType::class, $birthday);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($birthday);
            $em->flush();
            $this->addFlash('success', 'Geburtstag wurde gespeichert!');
            return $this->redirectToRoute('birthday');
        }
        return $this->render('closed_area/Birthday/form.html.twig', ['form' => $form->createView(), 'page_title' => 'Geburtstag bearbeiten']);
    }

    /**
     * @Route("/birthday/delete/{birthday}/{confirm}", name="birthday_delete",defaults={"confirm"=false})
     * @param Birthday $birthday
     * @param bool $confirm
     * @return Response
     */
    public function delete(Birthday $birthday, $confirm = false)
    {
        if ($confirm == false) {
            return $this->render('closed_area/confirm.html.twig', ['type' => 'Geburtstag']);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($birthday);
        $em->flush();
        $this->addFlash('success', 'Geburtstag wurde gelöscht!');
        return $this->redirectToRoute('birthday');

    }
}
