<?php

namespace App\Controller\Admin;

use App\Entity\Groupe;
use App\Form\GroupeType;
use App\Repository\GroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminGroupeController extends AbstractController
{
    /**
     * @Route("admin/groupes", name="admin_groupe_list")
     */
    public function adminGroupeList(GroupeRepository $groupeRepository)
    {
        $groupes = $groupeRepository->findAll();
        return $this->render("front/groupes.html.twig", ["groupes" => $groupes]);
    }

    /**
     * @Route("admin/groupe/{id}", name="admin_show_groupe")
     */
    public function adminShowGroupe($id, GroupeRepository $groupeRepository)
    {
        $groupe = $groupeRepository->find($id);
        return $this->render("front/groupe.html.twig", ["groupe" => $groupe]);
    }

    /**
     * @Route("admin/create/groupe", name="admin_create_groupe")
     */
    public function adminCreateGroupe(
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ){
        $groupe = new Groupe();
        $groupeForm = $this->createForm(GroupeType::class, $groupe);
        $groupeForm->handleRequest($request);

        if($groupeForm->isSubmitted() && $groupeForm->isValid()){
            $entityManagerInterface->persist($groupe);
            $entityManagerInterface->flush();
            return $this->redirectToRoute("admin_groupe_list");
        }
        return $this->render("admin/groupeform.html.twig", ['groupeForm' => $groupeForm->createView()]);
    }

    /**
     * @Route("admin/update/groupe", name="admin_update_groupe")
     */
    public function adminUpdateGroupe(
        $id,
        GroupeRepository $groupeRepository,
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ){
        $groupe = $groupeRepository->find($id);
        $groupeForm = $this->createForm(GroupeType::class, $groupe);
        $groupeForm->handleRequest($request);

        if($groupeForm->isSubmitted && $groupeForm->isValid()){
            $entityManagerInterface->persist($groupe);
            $entityManagerInterface->flush();
            return $this->redirectToRoute("admin_groupe_list");
        }
        return $this->render("admin/groupeform.html.twig", ['groupeForm' => $groupeForm->createView()]);
    }

    /**
     * @Route("admin/delete/groupe", name="admin_delete_groupe")
     */
    public function adminDeleteGroupe(
        $id,
        GroupeRepository $groupeRepository,
        EntityManagerInterface $entityManagerInterface
    ){
        $groupe = $groupeRepository->find($id);
        $entityManagerInterface->remove($groupe);
        $entityManagerInterface->flush();
        return $this->redirectToRoute("admin_groupe_list");
    }
}