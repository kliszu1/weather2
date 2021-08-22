<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Description of DefaultController
 *
 * @author Kliszu
 */
class DefaultController extends AbstractController {
    
    /**
    * @Route("/weather", name="weather")
    */
    public function index()
    {
        return new Response(
            '<html><body>babcia frania</body></html>'
        );
    }
}
