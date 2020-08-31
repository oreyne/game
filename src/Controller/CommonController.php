<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
* @Route("/common", name="common_")
*/
class CommonController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request)
    {
    	$this->addFlash(
            'notice',
            'Your changes were saved!'
        );
        
        return $this->render('common/index.html.twig', [
            'controller_name' => 'CommonController',
        ]);
    }

    /**
     * @Route("/show", name="show")
     */
    public function show(Request $request)
    {
        return $this->render('common/show.html.twig', [
            'controller_name' => 'show method',
        ]);
    }
}
