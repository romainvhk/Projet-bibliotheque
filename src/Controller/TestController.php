<?php

namespace App\Controller;

use App\Repository\EmprunteurRepository;
use App\Repository\LivreRepository;
use App\Repository\UserRepository;
use App\Entity\Emprunteur;
use App\Entity\Livre;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/test', name: 'app_test')]
class TestController extends AbstractController
{

   #[Route('/user', name: 'app_test_user')]
   public function user(ManagerRegistry $doctrine, UserRepository $repository): Response {

      $user = $repository->findAllOrderByName();
      dump($user);

      $user1 = $repository->find(1);
      dump($user1);

      $onlyUser = $repository->findOnlyUser();
      dump($onlyUser);
      exit();
   }

   #[Route('/livre', name: 'app_test_livre')]
   public function livre(ManagerRegistry $doctrine, LivreRepository $repository): Response {
      
      $em = $doctrine->getManager();

      $livres = $repository->findAllOrderByTitle();
      dump($livres);

      $livre1 = $repository->find(1);
      dump($livre1);

      $loremTitle = $repository->findWithLorem();
      dump($loremTitle);

      $newLivre = new Livre();
      $newLivre->setTitre('Totum autem id externum');
      $newLivre->setAnneeEdition(2020);
      $newLivre->setNombrePages(300);
      $newLivre->setCodeIsbn("9790412882714");
      // $newLivre->setAuteur('Hugues Cartier');

      $em->persist($newLivre);
      $em->flush();

      dump($newLivre);

      $livre2 = $repository->find(2);
      $livre2->setTitre('Aperiendum est igitur');
      dump($livre2);

      $livre123 = $repository->find(123);
      if($livre123) {
         try{
            $em->remove($livre123);
            $em->flush();
         } catch (Exception $e){
            dump($e->getMessage());
            dump($e->getCode());
            dump($e->getFile());
            dump($e->getLine());
            dump($e->getTraceAsString());
         }
      };

      exit();
   }


   #[Route('/emprunteur', name: 'app_test_emprunteur')]
   public function emprunteur(ManagerRegistry $doctrine, EmprunteurRepository $repository): Response {
      
      $emprunteurs = $repository->findAllOrderByName();
      dump($emprunteurs);
      
      exit();
   }
}
