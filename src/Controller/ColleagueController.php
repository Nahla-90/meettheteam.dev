<?php

namespace App\Controller;

use App\Entity\Colleague;
use App\Form\ColleagueType;
use App\Repository\ColleagueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/colleague")
 */
class ColleagueController extends BaseController
{
    /**
     * @Route("/", name="colleague_index", methods={"GET"})
     */
    public function index(Request $request, ColleagueRepository $colleagueRepository): Response
    {
        /* Check if User is Authorized */
        if (!$this->isAuthorized($request)) {
            return $this->render('security/unAuthorizedAccess.html.twig');
        }

        /* Render index view of colleague list */
        return $this->render('colleague/index.html.twig', [
            'colleagues' => $colleagueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="colleague_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        /* Check if User is Authorized */
        if (!$this->isAuthorized($request)) {
            return $this->render('security/unAuthorizedAccess.html.twig');
        }

        /* Prepare New  Colleague Form*/
        $colleague = new Colleague();
        $form = $this->createForm(ColleagueType::class, $colleague);
        $form->handleRequest($request);

        /* Check if form submitted and is valid*/
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($colleague);
            $entityManager->flush();

            return $this->redirectToRoute('colleague_index');
        }

        /* Render new colleague form view */
        return $this->render('colleague/new.html.twig', [
            'colleague' => $colleague,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="colleague_show", methods={"GET"})
     */
    public function show(Request $request,Colleague $colleague): Response
    {
        /* Check if User is Authorized */
        if (!$this->isAuthorized($request)) {
            return $this->render('security/unAuthorizedAccess.html.twig');
        }

        /* Render show colleague view */
        return $this->render('colleague/show.html.twig', [
            'colleague' => $colleague,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="colleague_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Colleague $colleague): Response
    {
        /* Check if User is Authorized */
        if (!$this->isAuthorized($request)) {
            return $this->render('security/unAuthorizedAccess.html.twig');
        }

        /* Prepare Edit Colleague Form*/
        $form = $this->createForm(ColleagueType::class, $colleague);
        $form->handleRequest($request);

        /* Check if form submitted and is valid*/
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('colleague_index');
        }

        /* Render edit colleague form view */
        return $this->render('colleague/edit.html.twig', [
            'colleague' => $colleague,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="colleague_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Colleague $colleague): Response
    {
        /* Check if User is Authorized */
        if (!$this->isAuthorized($request)) {
            return $this->render('security/unAuthorizedAccess.html.twig');
        }

        if ($this->isCsrfTokenValid('delete' . $colleague->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($colleague);
            $entityManager->flush();
        }

        /* Redirect to colleague list*/
        return $this->redirectToRoute('colleague_index');
    }
}
