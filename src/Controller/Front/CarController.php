<?php

namespace App\Controller\Front;

use App\Entity\Like;
use App\Entity\Dislike;
use App\Repository\CarRepository;
use App\Repository\LikeRepository;
use App\Repository\DislikeRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * @Route("like/car/{id}", name="car_like")
     */
    public function likeCar(
        $id,
        CarRepository $carRepository,
        LikeRepository $likeRepository,
        EntityManagerInterface $entityManagerInterface,
        DislikeRepository $dislikeRepository
    ){
        $car = $carRepository->find($id);
        $user = $this->getUser();

        if ($car->isLikedByUser($user)) {
            $like = $likeRepository->findOneBy([
                'car' => $car,
                'user' => $user
            ]);
            $entityManagerInterface->remove($like);
            $entityManagerInterface->flush();
        }

        if ($car->isDislikedByUser($user)) {
            $dislike = $dislikeRepository->findOneBy([
                'car' => $car,
                'user' => $user
            ]);
            $entityManagerInterface->remove($dislike);

            $like = new Like();

            $like->setCar($car);
            $like->setUser($user);

            $entityManagerInterface->persist($like);
            $entityManagerInterface->flush();
        }

        $like = new Like();

        $like->setCar($car);
        $like->setUser($user);

        $entityManagerInterface->persist($like);
        $entityManagerInterface->flush();
    }

    /**
     * @Route("dislike/car/{id}", name="car_dislike")
     */
    public function dislikeCar(
        $id,
        CarRepository $carRepository,
        EntityManagerInterface $entityManagerInterface,
        DislikeRepository $dislikeRepository,
        LikeRepository $likeRepository
    ){
        $car = $carRepository->find($id);
        $user = $this->getUser();

        if ($car->isDislikedByUser($user)) {
            $dislike = $dislikeRepository->findOneBy([
                'car' => $car,
                'user' => $user
            ]);

            $entityManagerInterface->remove($dislike);
            $entityManagerInterface->flush();
        }

        if ($car->isLikedByUser($user)) {
            $like =$likeRepository->findOneBy([
                'car' => $car,
                'user' => $user
            ]);

            $entityManagerInterface->remove($like);

            $dislike = new Dislike();
            $dislike->setCar($car);
            $dislike->setUser($user);

            $entityManagerInterface->persist($dislike);
            $entityManagerInterface->flush();
        }

        $dislike = new Dislike();

        $dislike->setCar($car);
        $dislike->setUser($user);

        $entityManagerInterface->persist($dislike);
        $entityManagerInterface->flush();        
    }
}