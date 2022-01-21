<?php

namespace App\Controller\Front;

use App\Repository\GroupeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeController extends AbstractController
{
    /**
     * @Route("groupes", name="groupe_list")
     */
    public function groupeList(GroupeRepository $groupeRepository)
    {
        $groupes = $groupeRepository->findAll();
        return $this->render("front/groupes.html.twig", ["groupes" => $groupes]);
    }

    /**
     * @Route("groupe/{id}", name="show_groupe")
     */
    public function showGroupe($id, GroupeRepository $groupeRepository)
    {
        $groupe = $groupeRepository->find($id);
        return $this->render("front/groupe.html.twig", ["groupe" => $groupe]);
    }
}