<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_ADMIN")]
class LivreController extends AbstractController
{
    #[Route('/admin/livre', name: 'app_livre')]
    public function index(): Response
    {
        return $this->render('livre/index.html.twig', [
            'controller_name' => 'LivreController',
        ]);
    }

    #[Route('/admin/livres', name: 'app_livre_list_all')]
    //#[IsGranted("ROLE_ADMIN")]
    public function listAll(LivreRepository $rep) {


        $livres = $rep ->findAll();
        //var_dump($livres);
        $l = new Livre();
        return $this->render('livre/listlivres.html.twig', [
            'livres' => $livres,
        ]);
    }

    #[Route('/admin/livre/{id<\d+>}', name: 'app_livre_find')]
    public function rechercher(Livre $livre) {
        return $this->render('livre/livre.html.twig', [
            'livre' => $livre,
        ]);
    }

    #[Route('/admin/livre/add', name: 'app_livre_add')]
    public function ajouter(EntityManagerInterface $em): Response {
        $livre = new Livre();
        $livre->setTitre("Lewis hamilton the goat")
              ->setSlug("lewis-hamilton-the-goat")
              ->setISBN("44")
              ->setQte(44)
              ->setImage("https://th.bing.com/th/id/OIP.3ggFYNnGQYza4K0yjKZtEAHaE8?rs=1&pid=ImgDetMain")
              ->setPrix(44,8)
              ->setResume("Lewis Hamilton The Formula 1 Owner...")
              ->setDateEdition(new \DateTime('2003-5-7'))
              ->setEditeur("Azer Ben Azzouz");
        $em->persist($livre);
        $em->flush();
        return new Response("Le Livre est enregistre avec succés");
    }

    #[Route('/admin/livre/delete/{id<\d+>}', name: 'app_livre_find')]
    public function delete(Livre $livre,EntityManagerInterface $em) : Response{
        $em->remove($livre);
        $em->flush();
        return $this->redirectToRoute('app_livre_list_all');
    }

    #[Route('/admin/livre/editer/{id<\d+>}', name: 'app_livre_find')]
    public function editer(Livre $livre,EntityManagerInterface $em) : Response{
        #$livre->setPrix($livre->getPrix()*1,1);
        #$em->persist($livre);
        $em->getRepository(Livre::class)
            ->find($livre)
            ->setPrix($livre->getPrix()*1.1);
        $em->flush();
        return $this->redirectToRoute('app_livre_list_all');
    }

    #[Route('/admin/livre/add', name: 'app_livre_add')]
    public function add(EntityManagerInterface $em,Request $request): Response
    {
        $livre = new Livre();
        // creation d'un objet formulaire
        $form = $this->createForm(LivreType::class,$livre);
        //Récupération et traitement des données
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($livre);
            $em->flush();
            return $this->redirectToRoute('app_livre_list_all');
        }
        //affichage du formulaire
        return $this->render('livre/create.html.twig',[
            'F1' => $form,
        ]);
    }
}
