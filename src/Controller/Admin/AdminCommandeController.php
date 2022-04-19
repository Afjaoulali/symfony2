<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(): Response
    {
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }

    /**
     * @Route("admin/commandes", name="admin_commandes_list")
     */                             // autowire
     public function commandeList(CommandeRepository $commandeRepository)
     {
         $commandes = $commandeRepository->findAll();
 
         return $this->render("admin/admin_commandes_list.html.twig", ['commandes' => $commandes]);
     }

     
     /**
     * @Route("admin/commande/{id}", name="admin_commande_show")
     */
    public function commandeShow(CommandeRepository $commandeRepository, $id)
    {
        $commande = $commandeRepository->find($id);

        return $this->render("admin/admin_commande_show.html.twig", ['commande' => $commande]);
    }

    /**
     * @Route("admin/create/commande", name="create_admin_commande")
     */
    public function createCommande(
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) {
        $commande = new Commande();

        // Création du formulaire
        $commandeForm = $this->createForm(CommandeType::class, $commande);

        // HandleRequest permet de récupérer les informations rentrées dans le formulaire
        // et de les traiter
        $commandeForm->handleRequest($request);

        if ($commandeForm->isSubmitted() && $commandeForm->isValid()) {
            // la fonction persist va regarder ce que l'on a fait sur Commande et
            // réaliser le code pour faire le CREATE ou le UPDATE en fonction de l'origine de la commande 
            $entityManagerInterface->persist($commande);
            // la fonction flush enregistre dans la bdd.
            $entityManagerInterface->flush();

            return $this->redirectToRoute('admin_commandes_list');
        }

        return $this->render('admin/admin_commande_form.html.twig', ['commandeForm' => $commandeForm->createView()]);
    }

    /**
     * @Route("update/admin/commande/{id}", name="admin_commande_update")
     */
    public function commandeUpdate(
        $id,
        CommandeRepository $commandeRepository,
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) {
        $commande = $commandeRepository->find($id);

        $commandeForm = $this->createForm(CommandeType::class, $commande);

        $commandeForm->handleRequest($request);

        if ($commandeForm->isSubmitted() && $commandeForm->isValid()) { 
            $entityManagerInterface->persist($commande);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('admin_commandes_list');
        }

        return $this->render('admin/admin_commande_form.html.twig', ['commandeForm' => $commandeForm->createView()]);
    }

    /**
     * @Route("delete/admin/commande/{id}", name="delete_admin_commande")
     */
    public function deleteCommande(
        $id,
        EntityManagerInterface $entityManagerInterface,
        CommandeRepository $commandeRepository
    ) {
        $commande = $commandeRepository->find($id);

        // remove supprime la commande
        $entityManagerInterface->remove($commande);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('admin_commandes_list');
    }
}
