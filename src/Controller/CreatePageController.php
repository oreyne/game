<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class CreatePageController extends AbstractController
{
    /**
     * @Route("/create/page", name="create_page", methods={"GET","HEAD"})
     */
    public function index()
    {
    	$number = random_int(0, 100);

        return $this->render('create_page/index.html.twig', [
            'controller_name' => 'CreatePageController',
            'number' => $number
        ]);

        // return new Response(
        //     '<html><body>Lucky number: '.$number.'</body></html>'
        // );
    }

	/**
     * @Route("/show/data/{data}", name="show_data", methods={"POST", "GET", "HEAD"})
     */
    public function showData(string $data) {
    	return $this->render(
    		'create_page/show_data.html.twig', [
    			'data' => $data
    		]
    	);
    }

    /**
     * @Route("/value/{data}", name="integer_value", methods={"POST", "GET", "HEAD"}, requirements={"data"="\d+"} )
     */
    public function intergerValue(int $data) {
    	return $this->render(
    		'create_page/integer.html.twig', [
    			'data' => 'the value is a integer'
    		]
    	);
    }

    /**
     * @Route("/value/{data}", name="string_value", methods={"POST", "GET", "HEAD"})
     */
    public function stringValue(string $data) {
    	return $this->render(
    		'create_page/show_data.html.twig', [
    			'data' => 'the value is a string'
    		]
    	);
    }

    /**
     * This route has a greedy pattern and is defined first.
     *
     * @Route("/blog/{slug}", name="blog_normal")
     */
    public function normal(string $slug)
    {
        return $this->render(
    		'create_page/priority.html.twig', [
    			'priority' => '0'
    		]
    	);
    }

    /**
     * This route could not be matched without defining a higher priority than 0.
     *
     * @Route("/blog/major", name="blog_major", priority=2)
     */
    public function major()
    {
        return $this->render(
    		'create_page/priority.html.twig', [
    			'priority' => '1',
    			'optional' => null
    		]
    	);
    }

    /**
     * @Route("/blogs/{page}/{title}", name="blog_index", defaults={"page": 21, "title": "Hello world!"})
     */
    public function beirut(int $page, string $title)
    {
        return $this->render(
    		'create_page/priority.html.twig', [
    			'priority' => $page,
    			'optional' => $title
    		]
    	);
    }
}
