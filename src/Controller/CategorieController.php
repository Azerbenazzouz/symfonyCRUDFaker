<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategorieController extends AbstractController
{
    #[Route('/admin/categorie', name: 'app_categorie')]
    public function index(CategorieRepository $rep): Response
    {
        $categories = $rep->findAll();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/admin/categorie/add', name: 'app_categorie_add')]
    public function add(EntityManagerInterface $em,Request $request): Response
    {
        $cat = new Categorie();
        // creation d'un objet formulaire
        $form = $this->createForm(CategorieType::class,$cat);
        //Récupération et traitement des données
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($cat);
            $em->flush();
            return $this->redirectToRoute('app_categorie');
        }
        //affichage du formulaire
        return $this->render('categorie/create.html.twig',[
           'F1' => $form,
        ]);
    }

    #[Route('/admin/categorie/update/{id}', name: 'app_categorie_update')]
    public function update(Categorie $cat,EntityManagerInterface $em,Request $request): Response
    {
        //$cat = new Categorie();
        // creation d'un objet formulaire
        $form = $this->createForm(CategorieType::class,$cat);
        //Récupération et traitement des données
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($cat);
            $em->flush();
            return $this->redirectToRoute('app_categorie');
        }
        //affichage du formulaire
        return $this->render('categorie/update.html.twig',[
            'F1' => $form,
        ]);
    }
}
