<?php

namespace App\Controller\Admin;

use App\Entity\Vehicule;
use App\Form\VehiculeType;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminVehiculeController extends AbstractController
{
    public function listVehicule(VehiculeRepository $vehiculeRepository)
    {
        $vehicules = $vehiculeRepository->findAll();

        return $this->render("admin/vehicule_list.html.twig", ['vehicules' => $vehicules]);
    }

    public function showVehicule($id, vehiculeRepository $vehiculeRepository)
    {
        $vehicule = $vehiculeRepository->find($id);

        return $this->render("admin/vehicule_show.html.twig", ['vehicule' => $vehicule]);
    }

    public function createVehicule(Request $request, EntityManagerInterface $entityManagerInterface,
    SluggerInterface $sluggerInterface)
    {
        $vehicule = new Vehicule();

        $vehiculeForm = $this->createForm(VehiculeType::class, $vehicule);

        $vehiculeForm->handleRequest($request);

        if ($vehiculeForm->isSubmitted() && $vehiculeForm->isValid()) {
           
            $mediaFile = $vehiculeForm->get('photo')->getData();
           
            if ($mediaFile) {

                // On va crée un nom unique et valide à notre fichier à partir du nom original
                // pour éviter tout problème de confusion.

                // On récupère le nom original du fichier.
                $originalFielname = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);

                // On utilise le slug sur le nom original pour avoir un nom valide du fichier.
                $safeFilename = $sluggerInterface->slug($originalFielname);

                // On ajoute un id unique au nom du fichier
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $mediaFile->guessExtension();

                $mediaFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                $vehicule->setPhoto($newFilename);
            }
           
            $entityManagerInterface->persist($vehicule);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_list_vehicule");
        }

        return $this->render("admin/vehicule_form.html.twig", ['vehiculeForm' => $vehiculeForm->createView()]);
    }

    public function updateVehicule(
        $id,
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        VehiculeRepository $vehiculeRepository,
        SluggerInterface $sluggerInterface
    ) {

        $vehicule = $vehiculeRepository->find($id);

        $vehiculeForm = $this->createForm(VehiculeType::class, $vehicule);

        $vehiculeForm->handleRequest($request);

        if ($vehiculeForm->isSubmitted() && $vehiculeForm->isValid()) {
            $mediaFile = $vehiculeForm->get('photo')->getData();
           
            if ($mediaFile) {

                // On va crée un nom unique et valide à notre fichier à partir du nom original
                // pour éviter tout problème de confusion.

                // On récupère le nom original du fichier.
                $originalFielname = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);

                // On utilise le slug sur le nom original pour avoir un nom valide du fichier.
                $safeFilename = $sluggerInterface->slug($originalFielname);

                // On ajoute un id unique au nom du fichier
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $mediaFile->guessExtension();

                $mediaFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                $vehicule->setPhoto($newFilename);
            }
            
            $entityManagerInterface->persist($vehicule);


            $entityManagerInterface->flush();

            return $this->redirectToRoute("admin_list_vehicule");
        }

        return $this->render("admin/vehicule_form.html.twig", ['vehiculeForm' => $vehiculeForm->createView()]);
    }

    public function deleteVehicule(
        $id,
        EntityManagerInterface $entityManagerInterface,
        VehiculeRepository $vehiculeRepository
    ) {
        $vehicule = $vehiculeRepository->find($id);

        $entityManagerInterface->remove($vehicule);

        $entityManagerInterface->flush();

        return $this->redirectToRoute("admin_list_vehicule");
    }
}
