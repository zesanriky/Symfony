<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
	/**
	 * @Route("/", name="welcome")
	 * @param LoggerInterface $logger
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function index(LoggerInterface $logger)
    {
	    $logger->error( "Welcome");
        return $this->render('welcome/index.html.twig', [
            'controller_name' => 'WelcomeController',
        ]);
    }
}
