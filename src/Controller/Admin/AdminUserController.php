<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    #[Route('/admin/user', name: 'app_admin_user')]
    public function index(): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'controller_name' => 'AdminUserController',
        ]);
    }

    /**
     * @Route("admin/users", name="admin_users_list")
     */                             // autowire
    public function userList(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render("admin/admin_users_list.html.twig", ['users' => $users]);
    }


    /**
     * @Route("admin/user/{id}", name="admin_user_show")
     */
    public function usershow(UserRepository $userRepository, $id)
    {
        $user = $userRepository->find($id);

        return $this->render("admin/admin_user_show.html.twig", ['user' => $user]);
    }

    /**
     * @Route("create/admin/user", name="create_admin_user")
     */
    public function createUser(
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) {
        $user = new User();

        // Création du formulaire
        $userForm = $this->createForm(UserType::class, $user);

        // HandleRequest permet de récupérer les informations rentrées dans le formulaire
        // et de les traiter
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // la fonction persist va regarder ce que l'on a fait sur category et
            // réaliser le code pour faire le CREATE ou le UPDATE en fonction de l'origine du produit  
            $entityManagerInterface->persist($user);
            // la fonction flush enregistre dans la bdd.
            $entityManagerInterface->flush();

            return $this->redirectToRoute('admin_users_list');
        }

        return $this->render('admin/admin_user_form.html.twig', ['userForm' => $userForm->createView()]);
    }

    /**
     * @Route("update/admin/user/{id}", name="admin_user_update")
     */
    public function userUpdate(
        $id,
        UserRepository $userRepository,
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) {
        $user = $userRepository->find($id);

        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('admin_users_list');
        }

        return $this->render('admin/admin_user_form.html.twig', ['userForm' => $userForm->createView()]);
    }

    /**
     * @Route("delete/admin/user/{id}", name="delete_admin_user")
     */
    public function deleteUser(
        $id,
        EntityManagerInterface $entityManagerInterface,
        UserRepository $userRepository
    ) {
        $user = $userRepository->find($id);

        // remove supprime la categorie
        $entityManagerInterface->remove($user);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('admin_users_list');
    }
}
