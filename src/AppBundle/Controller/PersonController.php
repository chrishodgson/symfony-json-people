<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Person;
use AppBundle\Model\PersonModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PersonController extends Controller
{
    /**
     * the number of people to display per page
     */
    const PEOPLE_PER_PAGE = 2;

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $people = (new PersonModel)->all();

        $deleteForms = array_map(
            function ($person) {
                return $this->createDeleteForm($person)->createView();
            }, $people
        );

        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $people,
            $request->query->getInt('page', 1),
            self::PEOPLE_PER_PAGE
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
        $person = new Person();
        $form = $this->createForm('AppBundle\Form\PersonType', $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            (new PersonModel)->add($form->getData());

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
        $person = (new PersonModel)->get($id);

        $deleteForm = $this->createDeleteForm($person);
        $editForm = $this->createForm('AppBundle\Form\PersonType', $person);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            (new PersonModel)->update($editForm->getData());

            return $this->redirectToRoute('person_index');
        }

        return $this->render('person/edit.html.twig', [
            'person' => $person,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param int $id - the person Id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, int $id): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $person = (new PersonModel)->get($id);

        $form = $this->createDeleteForm($person);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            (new PersonModel)->delete($id);
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
