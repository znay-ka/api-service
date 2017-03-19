<?php

namespace NanoServiceBundle\Entity;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class Polygon
 * Класс работы с многоугольником
 * @package NanoServiceBundle\Entity
 */
class Polygon
{
    const MIN_POINTS_COUNT = 3;
    protected $points;

    function __construct() {
    }

    /**
     * Функция заполнения точек многоугольника из запроса
     * @param $request
     */
    public function fillPointsFromRequest($request) {
        $points = [];
        foreach ($request->request as $index=>$coords) {
            $points[$index]=json_decode($coords);
            if(!( property_exists($points[$index], 'x') and property_exists($points[$index], 'y')))
                throw new Exception("Wrong coordinates, point ".$index);
        }
        if(count($points) < self::MIN_POINTS_COUNT) throw new Exception("This is not a polygon! Set up 3 or more points.");
        $this->points = $points;
    }

    /**
     * Функция заполнения точек многоугольника из json объекта
     * @param $json
     */
    public function fillPointsFromJSON($json) {
        $points = [];
        foreach ($json->polygon as $index=>$coords) {
            $points[$index]=$coords;
            if(!( property_exists($points[$index], 'x') and property_exists($points[$index], 'y')))
                throw new Exception("Wrong coordinates, point ".$index);
        }
        if(count($points) < self::MIN_POINTS_COUNT) throw new Exception("This is not polygon! Set up 3 or more points.");
        $this->points = $points;
    }

    /**
     * Получения массива объектов типа "точка"
     * @return array
     */
    public function getPoints() {
        if(empty($this->points)) throw new Exception("Polygon not set!");
        return $this->points;
    }

    /**
     * @param Получения объекта типа "точка" по индексу
     * @return object
     */
    private function getPointByIndex($index) {
        if(isset($this->points[$index])) return $this->points[$index];
        else throw new Exception("Try to get point with unexpected index");
    }

    /**
     * Расчёт дистанции между точками
     * @param $from object объект типа "точка"
     * @param $to object объект типа "точка"
     * @return float
     */
    private function getDistanceBetweenPoints($from, $to) {
        if(null===$from or null===$from) throw new Exception("Recived null point");
        return sqrt(abs($from->x - $to->x)**2 + abs($from->y - $to->y)**2);
    }

    /**
     * Расчёт дистанции между точками по их индексам
     * @param $indexFrom
     * @param $indexTo
     * @return float
     */
    public function getDistance($indexFrom, $indexTo) {
        $from = $this->getPointByIndex($indexFrom);
        $to = $this->getPointByIndex($indexTo);
        return $this->getDistanceBetweenPoints($from, $to);
    }

    /**
     * Получение периметра объекта
     * @return float
     */
    public function getPerimeter() {
        $points = $this->getPoints();
        $perimeter = 0;
        $firstPoint = reset($points);
        while(current($points)!==false) {
            $currPoint = current($points);
            $nextPoint = next($points);
            if(false === $nextPoint) { $nextPoint = $firstPoint; }
            $perimeter += $this->getDistanceBetweenPoints($currPoint, $nextPoint);
        }
        return $perimeter;
    }

}