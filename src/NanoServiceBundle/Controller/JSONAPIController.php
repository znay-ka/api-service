<?php
/**
 * Created by PhpStorm.
 * User: georgijsergusin
 * Date: 16.03.17
 * Time: 10:38
 */

namespace NanoServiceBundle\Controller;

use NanoServiceBundle\Entity\Polygon;
use NanoServiceBundle\NanoServiceBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
     * @IgnoreAnnotation("apiName")
     * @IgnoreAnnotation("apiGroup")
     * @IgnoreAnnotation("apiParamExample")
     * @IgnoreAnnotation("apiSuccessExample")
     * @IgnoreAnnotation("apiErrorExample")
*/

class JSONAPIController
{
    /**
     * @api {post} /api/json Получение периметра либо расстояния между точками
     * @apiName action
     * @apiGroup JSON
     *
     * @apiParamExample {json} Получить периметр:
     *  {
     *      "action":"perimeter",
     *      "polygon": {
     *          "1": {"x":10,"y":10},
     *          "2": {"x":20,"y":20},
     *          "3": {"x":20,"y":10}
     *      }
     *  }
     * @apiParamExample {json} Получить расстояние между точками:
     *  {
     *      "action":"distance",
     *      "polygon": {
     *          "1": {"x":10,"y":10},
     *          "2": {"x":20,"y":20},
     *          "3": {"x":20,"y":10}
     *      },
     *      "from":"1",
     *      "to":"2"
     *  }
     *
     * @apiSuccessExample {json} Успешный расчёт:
     *     HTTP/1.1 200 OK
     *     { "status":"success", "response":34.142135623731 }
     *
     * @apiErrorExample {json} Ошибка в процессе:
     *     HTTP/1.1 400 Bad request
     *     { "status":"error", "message":"Wrong json format" }
     *
     * @Route("/api/json")
     */
    public function processAction()
    {
        try {
            $request = Request::createFromGlobals();
            if($request->getMethod()!=="POST") {
                throw new Exception("Wrong request method");
            };

            $dataObject = json_decode($request->getContent());
            if(json_last_error()!==JSON_ERROR_NONE) throw new Exception("Wrong json format");

            if( empty($dataObject) or
                empty($dataObject->action) or
                empty($dataObject->polygon) )
                throw new Exception("Set up right request!");

            $polygon = new Polygon($dataObject->polygon);
            $polygon->fillPointsFromJSON($dataObject);

            $responseData = null;
            switch ($dataObject->action) {
                case "distance":
                    if(empty($dataObject->from) or empty($dataObject->to)) throw new Exception("Set up right request! (from-to parameters)");
                    $responseData = $polygon->getDistance($dataObject->from, $dataObject->to);
                    break;
                case "perimeter":
                    $responseData = $polygon->getPerimeter();
                    break;
                default:
                    throw new Exception("Set up action field!");
            }

            return new Response(
               json_encode([ "status" => "success",
                             "response" => $responseData ])
            );

        }
        catch (Exception $e) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $response->setContent( json_encode([    "status" => "error",
                                                    "message" => $e->getMessage() ]) );
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }
}