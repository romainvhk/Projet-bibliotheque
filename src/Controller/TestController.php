<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Auteur;
use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Entity\Livre;
use App\Repository\AuteurRepository;
use App\Repository\EmpruntRepository;
use App\Repository\EmprunteurRepository;
use App\Repository\LivreRepository;
use App\Repository\UserRepository;
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

      $auteurRepository = $doctrine->getRepository(Auteur::class);
      $auteur = $auteurRepository->find(2);
      $newLivre = new Livre();

      $newLivre->setTitre('Totum autem id externum');
      $newLivre->setAnneeEdition(2020);
      $newLivre->setNombrePages(300);
      $newLivre->setCodeIsbn("9790412882714");
      $newLivre->setAuteur($auteur);

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

      $em = $doctrine->getManager();

      $emprunteurs = $repository->findAllOrderByName();
      dump($emprunteurs);
      
      $emprunteur3 = $repository->find(3);
      dump($emprunteur3);

      $emprunteurFoo = $repository->findWhereFoo();
      dump($emprunteurFoo);
      
      exit();
   }

   #[Route('/emprunt', name: 'app_test_emprunt')]
   public function emprunt(ManagerRegistry $doctrine, EmpruntRepository $repository): Response {
      $em = $doctrine->getManager();

      $threeLastEmprunt = $repository->findThreeLast();
      dump($threeLastEmprunt);

      $secondEmpruntOfEmprunteur = $repository->allSecondEmprunteurEmprunt();
      dump($secondEmpruntOfEmprunteur);

      $thirdLivreEmprunt = $repository->thirdLivreEmprunt();
      dump($thirdLivreEmprunt);

      $notReturn = $repository->findNotReturn();
      dump($notReturn);

      $emprunteurRepository = $doctrine->getRepository(Emprunteur::class);
      $emprunteur = $emprunteurRepository->find(1);
      $livreRepository = $doctrine->getRepository(Livre::class);
      $livre = $livreRepository->find(1);
      $newEmprunt = new Emprunt();
      $newEmprunt->setDateEmprunt(DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-12-01 16:00:00'));
      $newEmprunt->setDateRetour(null);
      $newEmprunt->setEmprunteur($emprunteur);
      $newEmprunt->setLivre($livre);
      $em->persist($newEmprunt);
      $em->flush();

      dump($newEmprunt);

      $emprunt3 = $repository->find(3);
      $emprunt3->setDateRetour(DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-05-01 10:00:00'));
      $em->flush();
      dump($emprunt3);

      exit();
   }
}
