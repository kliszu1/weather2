<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\Weather\Strategy\WeatherStrategy;
use Symfony\Component\Config\Builder\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\ValueObject\ApiResponse;
use App\Dictionary\ResponseApiStatusesDictionary;
use App\Dictionary\MessagesDictionary;
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
        $apiResponse = new ApiResponse();
        
        $service = $weatherStrategy->selectService($_POST['serviceType']);
        
        if(is_object($service)){
            $apiResponse = $service
                ->setCity($_POST['city'])
                ->init($apiResponse);
            
            if($apiResponse->getStatus() === ResponseApiStatusesDictionary::KEY_STATUS_OK){
                $apiResponse->setHtml(
                    $this->renderView('weather/readyWeather.html.twig', ['weatherData' => $apiResponse->getData()])
                );
            } 
        } else {
            $apiResponse
                ->setStatus(ResponseApiStatusesDictionary::KEY_STATUS_ERROR)
                ->setMessage(MessagesDictionary::STATUS_NO_SERVICE_VALUE);
        }

        echo json_encode($apiResponse);
        exit;
    }
}
