<?php

namespace App\Controller\Front;


use App\Repository\VehiculeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontHomeController extends AbstractController
{
    /**
     * @Route("search/", name="front_search")
     */
    public function search(VehiculeRepository $vehiculeRepository, Request $request)
    {
        // Récupérer les informations du formulaire
        $term = $request->query->get('term');
        // query sert au formulaire en get, pour les formulaires post il faut utiliser request

        $vehicules = $vehiculeRepository->searchByTerm($term);

        return $this->render("front/search.html.twig", ['vehicules' => $vehicules, 'term' => $term]);
    }
}
