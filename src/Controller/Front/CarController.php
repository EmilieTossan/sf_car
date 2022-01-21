<?php

namespace App\Controller\Front;

use App\Repository\CarRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CarController extends AbstractController
{
    /**
     * @Route("cars", name="car_list")
     */
    public function carList(CarRepository $carRepository)
    {
        $cars = $carRepository->findAll();
        return $this->render("front/cars.html.twig", ["cars" => $cars]);
    }

    /**
     * @Route("car/{id}", name="show_car")
     */
    public function showCar($id, CarRepository $carRepository)
    {
        $car = $carRepository->find($id);
        return $this->render("front/car.html.twig", ["car" => $car]);
    }

    /**
     * @Route("search", name="front_search")
     */
    public function frontSearch(Request $request, CarRepository $carRepository)
    {
        $term = $request->query->get('term');
        $cars = $carRepository->searchByTerm($term);
        return $this->render('front/search.html.twig', ['cars' => $cars, 'term' => $term]);
    }
}