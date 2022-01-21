<?php

namespace App\Controller\Admin;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCarController extends AbstractController
{
    /**
     * @Route("admin/cars", name="admin_car_list")
     */
    public function adminCarList(CarRepository $carRepository)
    {
        $cars = $carRepository->findAll();
        return $this->render("front/cars.html.twig", ["cars" => $cars]);
    }

    /**
     * @Route("admin/car/{id}", name="admin_show_car")
     */
    public function adminShowCar($id, CarRepository $carRepository)
    {
        $car = $carRepository->find($id);
        return $this->render("front/car.html.twig", ["car" => $car]);
    }

    /**
     * @Route("admin/create/car", name="admin_create_car")
     */
    public function adminCreateCar(
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ){
        $car = new Car();
        $carForm = $this->createForm(CarType::class, $car);
        $carForm->handleRequest($request);

        if($carForm->isSubmitted() && $carForm->isValid()){
            $entityManagerInterface->persist($car);
            $entityManagerInterface->flush();
            return $this->redirectToRoute("admin_car_list");
        }
        return $this->render("admin/carform.html.twig", ['carForm' => $carForm->createView()]);
    }

    /**
     * @Route("admin/update/car", name="admin_update_car")
     */
    public function adminUpdateCar(
        $id,
        CarRepository $carRepository,
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ){
        $car = $carRepository->find($id);
        $carForm = $this->createForm(CarType::class, $car);
        $carForm->handleRequest($request);

        if($carForm->isSubmitted && $carForm->isValid()){
            $entityManagerInterface->persist($car);
            $entityManagerInterface->flush();
            return $this->redirectToRoute("admin_car_list");
        }
        return $this->render("admin/carform.html.twig", ['carForm' => $carForm->createView()]);
    }

    /**
     * @Route("admin/delete/car", name="admin_delete_car")
     */
    public function adminDeleteCar(
        $id,
        CarRepository $carRepository,
        EntityManagerInterface $entityManagerInterface
    ){
        $car = $carRepository->find($id);
        $entityManagerInterface->remove($car);
        $entityManagerInterface->flush();
        return $this->redirectToRoute("admin_car_list");
    }

    /**
     * @Route("admin/search", name="admin_search")
     */
    public function adminSearch(Request $request, CarRepository $carRepository)
    {
        $term = $request->query->get('term');
        $cars = $carRepository->searchByTerm($term);
        return $this->render('admin/search.html.twig', ['cars' => $cars, 'term' => $term]);
    }
}