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
 * @IgnoreAnnotation("apiParam")
 * @IgnoreAnnotation("apiExample")
 * @IgnoreAnnotation("apiSuccessExample")
 * @IgnoreAnnotation("apiErrorExample")
 */

class ServiceController
{
    /**
     * @api {post} /api/distance/from_:from/to_:to Получение расстояния между точками
     * @apiName distance
     * @apiGroup Form-data
     *
     * @apiExample {curl} Пример использования:
     *     curl -i http://localhost:8000/api/distance/from_2/to_3
     *
     * @apiParam {string} from ID начальной точки.
     * @apiParam {string} to ID конечной точки.
     * @apiParam {string} id_point_n Массив точек в параметрах form-data в виде id->{"x":<float>,"y":<float>}.
     *
     * @apiSuccessExample {json} Успешный расчёт:
     *     HTTP/1.1 200 OK
     *     14.142135623731
     *
     * @apiErrorExample {json} Ошибка в процессе:
     *     HTTP/1.1 400 Bad request
     *     Try to get point with unexpected index
     *
     * @Route("/api/distance/from_{from}/to_{to}")
     */
    public function distanceAction($from, $to)
    {
        try {
            $request = Request::createFromGlobals();
            if($request->getMethod()!=="POST") {
                throw new Exception("Wrong request method");
            };

            /** @var Polygon $polygon переменная - объект класса многоугольник */
            $polygon = new Polygon();
            $polygon->fillPointsFromRequest($request);

            $distance = $polygon->getDistance($from, $to);

            return new Response(
                $distance
            );

        }
        catch (Exception $e) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $response->setContent($e->getMessage());
            $response->headers->set('Content-Type', 'text/html');
            return $response;
        }
    }

    /**
     * @api {post} /api/perimeter Получение периметра многоугольника
     * @apiName perimeter
     * @apiGroup Form-data
     *
     * @apiParam {string} id_point_n Массив точек в параметрах form-data в виде id->{"x":<float>,"y":<float>}.
     *
     * @apiSuccessExample {json} Успешный расчёт:
     *     HTTP/1.1 200 OK
     *     34.142135623731
     *
     * @apiErrorExample {json} Ошибка в процессе:
     *     HTTP/1.1 400 Bad request
     *     Wrong coordinates
     *
     * @Route("/api/perimeter")
     */
    public function perimeterAction()
    {
        try {
            $request = Request::createFromGlobals();
            if($request->getMethod()!=="POST") {
                throw new Exception("Wrong request method");
            };

            /** @var Polygon $polygon переменная - объект класса многоугольник */
            $polygon = new Polygon();
            $polygon->fillPointsFromRequest($request);

            $perimeter = $polygon->getPerimeter();

            return new Response(
                $perimeter
            );

        }
        catch (Exception $e) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $response->setContent($e->getMessage());
            $response->headers->set('Content-Type', 'text/html');
            return $response;
        }
    }
}