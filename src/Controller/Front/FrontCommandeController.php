<?php

namespace App\Controller\Front;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontCommandeController extends AbstractController
{
     /**
     * @Route("commandes", name="list_commande")
     */
    public function listCommande(CommandeRepository $commandeRepository)
    {
        $commandes = $commandeRepository->findAll();

        return $this->render("front/commande_list.html.twig", ['commandes' => $commandes]);
    }

    /**
     * @Route("commande/{id}", name="show_commande")
     */
    public function showCommande($id, CommandeRepository $commandeRepository)
    {
        $commande = $commandeRepository->find($id);

        return $this->render("front/commande_show.html.twig", ['commande' => $commande]);
    }

     /**
     * @Route("create/commande", name="create_commande")
     */
    public function createCommande(
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) {
        $commande = new Commande();

        $commandeForm = $this->createForm(CommandeType::class, $commande);
        $commandeForm->handleRequest($request);

        if ($commandeForm->isSubmitted() && $commandeForm->isValid()) {

            $entityManagerInterface->persist($commande);

            $entityManagerInterface->flush();

            return $this->redirectToRoute('commandes_list');
        }

        return $this->render('front/commande_form.html.twig', ['commandeForm' => $commandeForm->createView()]);
    }
}

