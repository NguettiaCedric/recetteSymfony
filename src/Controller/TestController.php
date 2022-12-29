<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    #[Route('/test1', 'test.index', methods: ['GET'])]
    public function index() : Response
    {
        return $this->render('test.html.twig');
    }
}

