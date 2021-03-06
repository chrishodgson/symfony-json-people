<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Person;
use AppBundle\Model\PersonModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PersonController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $personModel = $this->get('person_model');
        
        $people = $personModel->all();

        $deleteForms = array_map(
            function ($person) {
                return $this->createDeleteForm($person)->createView();
            },
            $people
        );

        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $people,
            $request->query->getInt('page', 1),
            $this->container->getParameter('per_page')
        );

        return $this->render('person/index.html.twig', [
            'pagination' => $pagination,
            'delete_forms' => $deleteForms
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $personModel = $this->get('person_model');
        
        $person = new Person();
        $form = $this->createForm('AppBundle\Form\PersonType', $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personModel->add($form->getData());

            return $this->redirectToRoute('person_index');
        }

        return $this->render('person/new.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param int $id - the person Id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, int $id)
    {
        $personModel = $this->get('person_model');
        
        $person = $personModel->get($id);

        $form = $this->createForm('AppBundle\Form\PersonType', $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personModel->update($form->getData());

            return $this->redirectToRoute('person_index');
        }

        return $this->render('person/edit.html.twig', [
            'person' => $person,
            'edit_form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param int $id - the person Id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, int $id): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $personModel = $this->get('person_model');
        
        $person = $personModel->get($id);

        $form = $this->createDeleteForm($person);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $personModel->delete($id);
        }

        return $this->redirectToRoute('person_index');
    }

    /**
     * @param Person $person
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Person $person)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('person_delete', ['id' => $person->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
