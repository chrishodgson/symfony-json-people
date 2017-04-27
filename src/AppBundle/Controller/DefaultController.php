<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return $this->redirect($this->generateUrl('person_index'));
    }
}
