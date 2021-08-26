<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\Weather\Strategy\WeatherStrategy;
use Symfony\Component\Config\Builder\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Description of DefaultController
 *
 * @author Kliszu
 */
class DefaultController extends AbstractController {
    
    /**
    * @Route("/weather", name="weather")
    */
    public function index() : Response
    {
        return $this->render('weather/index.html.twig');
    }
    
    /**
    * @Route("/ajax", name="ajax", methods={"POST"})
    */
    public function getWeatherControler()
    {
        $weatherStrategy = new WeatherStrategy();
        $service = $weatherStrategy->selectService($_POST['serviceType']);

        if(!is_object($service)){
            echo json_encode('Brak Serwisu');
            exit;
        }
        
        $response = $service
            ->setCity($_POST['city'])
            ->init();

        if($response['status'] === 'error'){
            echo json_encode($response);
            exit;
        }

        echo json_encode([
            'status' => 'ok',
            'html' => $this->renderView('weather/readyWeather.html.twig', ['weatherData' => $response['data']])
        ]);
        exit;
    }
}
