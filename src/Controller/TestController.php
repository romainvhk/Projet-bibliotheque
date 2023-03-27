<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\EmprunteurRepository;
use App\Entity\Emprunteur;
use Doctrine\Persistence\ManagerRegistry;

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


   #[Route('/emprunteur', name: 'app_test_emprunteur')]
   public function emprunteur(ManagerRegistry $doctrine, EmprunteurRepository $repository): Response {
      
      $emprunteurs = $repository->findAllOrderByName();
      dump($emprunteurs);
      
      exit();
   }
}
