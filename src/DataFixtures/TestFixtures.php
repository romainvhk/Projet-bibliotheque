<?php

namespace App\DataFixtures;

use \DateTimeImmutable;
use App\Entity\User;
use App\Entity\Emprunteur;
use App\Entity\Auteur;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Emprunt;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class TestFixtures extends Fixture
{

    private $doctrine;
    private $faker;
    private UserPasswordHasherInterface $hasker;
    private $manager;

    public function __construct(ManagerRegistry $doctrine, UserPasswordHasherInterface $hasher)
    {
        $this->doctrine = $doctrine;
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public static function getGroups(): array
    {
        return ['test'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->loadAdmin();
        $this->loadEmprunteur();
        $this->loadAuteur();
        $this->loadGenre();
        $this->loadLivre();
        $this->loadEmprunt();
    }

    public function loadAdmin(): void {
        $datas = [
            [
                'email' => 'admin@example.com',
                'roles' => ['ROLE_ADMIN'],
                'password' => '123',
                'enabled' => true,
            ],
        ];
    
        foreach ($datas as $data) {
            $user = new User();
    
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);
            $user->setPassword($this->hasher->hashPassword($user, $data['password']));
            $user->setEnabled($data['enabled']);
    
            $this->manager->persist($user);
        };

        $this->manager->flush();
    }

    public function loadEmprunteur(): void {

        $datas = [
            [
                // user
                'email' => 'foo.foo@example.com',
                'roles' => ['ROLE_USER'],
                'password' => '123',
                'enabled' => true,
                // emprunteur
                'nom' => 'Foo',
                'prenom' => 'Foo',
                'tel' => '123456789',
                'createdAt' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s','2020-01-01 10:00:00'),
                'updatedAt' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s','2020-01-01 10:00:00'),
            ],
            [
                // user
                'email' => 'bar.bar@example.com',
                'roles' => ['ROLE_USER'],
                'password' => '123',
                'enabled' => false,
                // emprunteur
                'nom' => 'Bar',
                'prenom' => 'Bar',
                'tel' => '123456789',
                'createdAt' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 11:00:00'),
                'updatedAt' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-05-01 12:00:00'),
            ],
            [
                // user
                'email' => 'baz.baz@example.com',
                'roles' => ['ROLE_USER'],
                'password' => '123',
                'enabled' => true,
                // emprunteur
                'nom' => 'Baz',
                'prenom' => 'Baz',
                'tel' => '123456789',
                'createdAt' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-03-01 12:00:00'),
                'updatedAt' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 10:00:00'),
            ]
        ];

        foreach ($datas as $data) {
            $user = new User();// affectr mail et mdp
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);
            $user->setPassword($this->hasher->hashPassword($user, $data['password']));
            $user->setEnabled($data['enabled']);
            
            
            $emprunteur = new Emprunteur();
            $emprunteur->setNom($data['nom']);
            $emprunteur->setPrenom($data['prenom']);
            $emprunteur->setTel($data['tel']);
            $emprunteur->setCreatedAt($data['createdAt']);
            $emprunteur->setUpdatedAt($data['updatedAt']);
            $emprunteur->setUser($user);
            // mettre $user dans le set
            // il est possible de virer la fonction loadUser en l'intégrant dans loadEmprunteur. Doctrine va automatiquement créer les users avant grâce à la relation de clé étrangères. 

            $this->manager->persist($emprunteur);
        };

        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email());
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->hasher->hashPassword($user, $this->faker->password(8,20)));
            $user->setEnabled(true);

            $emprunteur = new Emprunteur();
            $emprunteur->setNom(ucfirst($this->faker->firstName($gender = 'male' | 'female')));
            $emprunteur->setPrenom(ucfirst($this->faker->lastName()));
            $emprunteur->setTel($this->faker->numerify('#########'));
            $emprunteur->setCreatedAt(new DateTimeImmutable());
            $emprunteur->setUpdatedAt(new DateTimeImmutable());
            $emprunteur->setUser($user);

            $this->manager->persist($emprunteur);
        };

        $this->manager->flush();
    }

    public function loadAuteur(): void {
     
        $datas = [
            [
                'nom' => 'auteur inconnu',
                'prenom' => '',
            ],
            [
                'nom' => 'Cartier',
                'prenom' => 'Hugues',
            ],
            [
                'nom' => 'Lambert',
                'prenom' => 'Armand',
            ],
            [
                'nom' => 'Moitessier',
                'prenom' => 'Thomas',
            ],
        ];
    
        foreach ($datas as $data) {
            $auteur = new Auteur();

            $auteur->setNom($data['nom']);
            $auteur->setPrenom($data['prenom']);

            $this->manager->persist($auteur);
        };


        for ($i = 0; $i < 500; $i++) {

            $auteur = new Auteur();

            $auteur->setNom(ucfirst($this->faker->word()));
            $auteur->setPrenom(ucfirst($this->faker->word()));


            $this->manager->persist($auteur);
        };


        $this->manager->flush();
    }

    public function loadGenre(): void {

        $datas = [
            [
                'nom' => 'poésie',
                'description' => NULL,
            ],
            [
                'nom' => 'nouvelle',
                'description' => NULL,
            ],
            [
                'nom' => 'roman historique',
                'description' => NULL,
            ],
            [
                'nom' => 'roman d\'amour',
                'description' => NULL,
            ],
            [
                'nom' => 'roman d\'aventure',
                'description' => NULL,
            ],
            [
                'nom' => 'science-fiction',
                'description' => NULL,
            ],
            [
                'nom' => 'fantasy',
                'description' => NULL,
            ],
            [
                'nom' => 'biographie',
                'description' => NULL,
            ],
            [
                'nom' => 'conte',
                'description' => NULL,
            ],
            [
                'nom' => 'témoignage',
                'description' => NULL,
            ],
            [
                'nom' => 'théâtre',
                'description' => NULL,
            ],
            [
                'nom' => 'essai',
                'description' => NULL,
            ],
            [
                'nom' => 'journal intime',
                'description' => NULL,
            ],
        ];
    
        foreach ($datas as $data) {

            $genre = new Genre();

            $genre->setNom($data['nom']);
            $genre->setDescription($data['description']);

            $this->manager->persist($genre);
        };


        $this->manager->flush();
    }

    public function loadLivre(): void {
        $repository = $this->manager->getRepository(Auteur::class);
        $auteur = $repository->findAll();

        $datas = [
            [
                'titre' => 'Lorem ipsum dolor sit amet',
                'annee_edition' => 2010,
                'nombre_page' => 100,
                'code_isbn' => '9785786930024',
                'auteur' => $auteur[0],
            ],
            [
                'titre' => 'Consectetur adipiscing elit',
                'annee_edition' => 2011,
                'nombre_page' => 150,
                'code_isbn' => '9783817260935',
                'auteur' => $auteur[1],
            ],
            [
                'titre' => 'Mihi quidem Antiochum',
                'annee_edition' => 2012,
                'nombre_page' => 200,
                'code_isbn' => '9782020493727',
                'auteur' => $auteur[2],
            ],
            [
                'titre' => 'Quem audis satis belle',
                'annee_edition' => 2013,
                'nombre_page' => 250,
                'code_isbn' => '9794059561353',
                'auteur' => $auteur[3],
            ],
        ];

        foreach($datas as $data) {
            $livre = new Livre();
            $livre->setTitre($data['titre']);
            $livre->setAnneeEdition($data['annee_edition']);
            $livre->setNombrePages($data['nombre_page']);
            $livre->setCodeIsbn($data['code_isbn']);
            $livre->setAuteur($data['auteur']);

            $this->manager->persist($livre);
        };

        for ($i = 0; $i < 1000; $i++) {
            $livre = new Livre();
            $livre->setTitre($this->faker->sentence(random_int(3,5)));
            $livre->setAnneeEdition($this->faker->year());
            $livre->setNombrePages($this->faker->numberBetween(100, 500));
            $livre->setCodeIsbn($this->faker->numerify('#############'));
            $livre->setAuteur($auteur[$this->faker->numberBetween(0, 500)]);

            $this->manager->persist($livre);
        }

        $this->manager->flush();
    }

    public function loadEmprunt(): void {
        $repository = $this->manager->getRepository(Emprunteur::class);
        $emprunteur = $repository->findAll();

        $repository = $this->manager->getRepository(Livre::class);
        $livre = $repository->findAll();


        $datas = [
            [
                'date_emprunt' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-02-01 10:00:00'),
                'date_retour' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-03-01 10:00:00'),
                'emprunteur' => $emprunteur[0],
                'livre' => $livre[0],
            ],
            [
                'date_emprunt' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-03-01 10:00:00'),
                'date_retour' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-04-01 10:00:00'),
                'emprunteur' => $emprunteur[1],
                'livre' => $livre[1],
            ],[
                'date_emprunt' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-02-01 10:00:00'),
                'date_retour' => null,
                'emprunteur' => $emprunteur[2],
                'livre' => $livre[2],
            ],
        ];

        foreach($datas as $data) {
            $emprunt = new Emprunt();
            $emprunt->setDateEmprunt($data['date_emprunt']);
            $emprunt->setDateRetour($data['date_retour']);
            $emprunt->setEmprunteur($data['emprunteur']);
            $emprunt->setLivre($data['livre']);

            $this->manager->persist($emprunt);
        };

        $this->manager->flush();
    }
}

