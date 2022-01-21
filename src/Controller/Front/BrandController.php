<?php

namespace App\Controller\Front;

use App\Repository\BrandRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BrandController extends AbstractController
{
    /**
     * @Route("brands", name="brand_list")
     */
    public function brandList(BrandRepository $brandRepository)
    {
        $brands = $brandRepository->findAll();
        return $this->render("front/brands.html.twig", ["brands" => $brands]);
    }

    /**
     * @Route("brand/{id}", name="show_brand")
     */
    public function showBrand($id, BrandRepository $brandRepository)
    {
        $brand = $brandRepository->find($id);
        return $this->render("front/brand.html.twig", ["brand" => $brand]);
    }
}