<?php

namespace PhotoalbumBundle\Controller;

use PhotoalbumBundle\Entity\Photo;
use PhotoalbumBundle\Entity\Photoalbum;
use PhotoalbumBundle\Form\PhotoalbumFormType;
use PhotoalbumBundle\Event\PhotoalbumEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PhotoController extends AbstractController
{
    /**
     * @Route("/photoalbums", name="photoalbum")
     * @return Response
     */
    public function indexAction()
    {

        $albums = $this->getDoctrine()->getRepository(Photoalbum::class)->findAllOrdered($this->isGranted('ROLE_STAMMI'));
        return $this->render('closed_area/Photoalbum/index.html.twig', ['photoalbums' => $albums]);
    }

    /**
     * @Route("/photoalbum/create", name="photoalbum_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        setlocale(LC_TIME, "de_DE");
        $photoalbum = new Photoalbum();
        $form = $this->createForm(PhotoalbumFormType::class, $photoalbum);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($photoalbum);
            $em->flush();

            $this->get('event_dispatcher')->dispatch(PhotoalbumEvent::NAME_CREATED, new PhotoalbumEvent($photoalbum, $this->getUser()));

            return $this->redirectToRoute('photoalbum');
        }
        return $this->render('closed_area/Photoalbum/form.html.twig', ['form' => $form->createView(), 'page_title' => 'Fotoalbum hinzufügen']);
    }


    /**
     * @Route("/photoalbum/edit/{photoalbum}", name="photoalbum_edit")
     * @param Photoalbum $photoalbum
     * @param Request $request
     * @return Response
     */
    public function edit(Photoalbum $photoalbum, Request $request)
    {
        $form = $this->createForm(PhotoalbumFormType::class, $photoalbum);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($photoalbum);
            $em->flush();;
            $this->get('event_dispatcher')->dispatch(PhotoalbumEvent::NAME_EDITED, new PhotoalbumEvent($photoalbum, $this->getUser()));
            return $this->redirectToRoute('photoalbum');
        }
        return $this->render('closed_area/Photoalbum/form.html.twig', ['form' => $form->createView(), 'page_title' => 'Fotoalbum bearbeiten']);
    }

    /**
     * @Route("/photoalbum/delete/{photoalbum}/{confirm}", name="photoalbum_delete",defaults={"confirm"=false})
     * @param Photoalbum $photoalbum
     * @param bool $confirm
     * @return Response
     */
    public function delete(Photoalbum $photoalbum, $confirm = false)
    {
        if ($confirm == false) {
            return $this->render('closed_area/confirm.html.twig', ['type' => 'Fotoalbum']);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($photoalbum);
        $em->flush();
        $this->get('event_dispatcher')->dispatch(PhotoalbumEvent::NAME_DELETED, new PhotoalbumEvent($photoalbum, $this->getUser()));
        return $this->redirectToRoute('photoalbum');

    }

    /**
     * @Route("/photoalbum/view/{photoalbum}", name="photoalbum_view")
     * @param Photoalbum $photoalbum
     * @return Response
     */
    public function view(Photoalbum $photoalbum)
    {
        return $this->render('closed_area/Photoalbum/view.html.twig', ['photoalbum' => $photoalbum]);
    }


    /**
     * @Route("/photo/display/{photo}/{thumb}", name="photo_display",defaults={"thumb"=false})
     * @param Photo $photo
     * @param bool $thumb
     * @param Request $oRequest
     * @return Response
     */
    public function display(Photo $photo, $thumb = false, Request $oRequest)
    {
        $oResponse = new Response();
        $dir = dirname(__FILE__) . '/../../../var/photos/';
        $dir .= $photo->album->id . '/';
        if ($thumb) {
            $file = $photo->thumbnail;
        } else {
            $file = $photo->photo;
        }
        $sFileName = $dir . $file;
        if (!is_file($sFileName)) {
            $oResponse->setStatusCode(404);

            return $oResponse;
        }

        // Caching...
        $sLastModified = filemtime($sFileName);
        $sEtag = md5_file($sFileName);

        $sFileSize = filesize($sFileName);
        $aInfo = getimagesize($sFileName);
        $type = $aInfo['mime'];
        if ($photo->type == 1) {
            $type = "video/mp4";
        }

        if (in_array($sEtag, $oRequest->getETags()) || $oRequest->headers->get('If-Modified-Since') === gmdate("D, d M Y H:i:s", $sLastModified) . " GMT") {
            $oResponse->headers->set("Content-Type", $type);
            $oResponse->headers->set("Last-Modified", gmdate("D, d M Y H:i:s", $sLastModified) . " GMT");
            $oResponse->setETag($sEtag);
            $oResponse->setPublic();
            $oResponse->setStatusCode(304);

            return $oResponse;
        }

        $oStreamResponse = new StreamedResponse();
        $oStreamResponse->headers->set("Content-Type", $type);
        $oStreamResponse->headers->set("Content-Length", $sFileSize);
        $oStreamResponse->headers->set("ETag", $sEtag);
        $oStreamResponse->headers->set("Last-Modified", gmdate("D, d M Y H:i:s", $sLastModified) . " GMT");

        $oStreamResponse->setCallback(function () use ($sFileName) {
            readfile($sFileName);
        });

        return $oStreamResponse;
    }

    /**
     * @Route("/photo/displayVideo/{photo}.mp4", name="photo_displayvideo")
     * @param Photo $photo
     * @return Response
     */
    public function displayVideo(Photo $photo)
    {
        $oResponse = new Response();
        $dir = dirname(__FILE__) . '/../../../var/photos/';
        $dir .= $photo->album->id . '/';

        $file = $photo->photo;

        $sFileName = $dir . $file;
        $size = filesize($sFileName);

        $fm = @fopen($sFileName, 'rb');
        if (!$fm) {
            $oResponse->setStatusCode(404);

            return $oResponse;
        }

        $begin = 0;
        $end = $size;

        if (isset($_SERVER['HTTP_RANGE'])) {
            if (preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $_SERVER['HTTP_RANGE'], $matches)) {
                $begin = intval($matches[1]);
                if (!empty($matches[2])) {
                    $end = intval($matches[2]);
                }
            }
        }


        // Caching...
        $sLastModified = filemtime($sFileName);
        $sEtag = md5_file($sFileName);

        $aInfo = getimagesize($sFileName);
        $type = $aInfo['mime'];
        if ($photo->type == 1) {
            $type = "video/mp4";
        }


        $oStreamResponse = new Response();

        if ($begin > 0 || $end < $size)
            $oStreamResponse->setStatusCode(206);
        else
            $oStreamResponse->setStatusCode(200);
        $oStreamResponse->headers->set("Content-Type", $type);
        $oStreamResponse->headers->set("Accept-Ranges", "bytes");
        $oStreamResponse->headers->set("Content-Disposition", "inline");
        $oStreamResponse->headers->set("Content-Transfer-Encoding", "binary\n");
        $oStreamResponse->headers->set("Content-Length", ($end - $begin));
        $oStreamResponse->headers->set("Content-Range",
            "bytes $begin-$end/$size");
        $oStreamResponse->headers->set("ETag", $sEtag);
        $oStreamResponse->headers->set("Last-Modified", gmdate("D, d M Y H:i:s", $sLastModified) . " GMT");

        $cur = $begin;
        fseek($fm, $begin, 0);

        $content = "";
        while (!feof($fm) && $cur < $end && (connection_status() == 0)) {
            $content .= fread($fm, min(1024 * 16, $end - $cur));
            $cur += 1024 * 16;
            usleep(1000);
        }


        return $oStreamResponse->setContent($content);
    }

}
