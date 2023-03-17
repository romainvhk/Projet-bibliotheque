<?php

namespace App\DataFixtures;

use \DateTimeImmutable;
use App\Entity\User;
use App\Entity\Emprunteur;
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

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->loadUser();
        $this->loadEmprunteur();
    }

    public function loadUser(): void {
        $datas = [
            [
                'email' => 'admin@example.com',
                'roles' => ['ROLE_ADMIN'],
                'password' => '123',
            ],
            [
                'email' => 'foo.foo@example.com',
                'roles' => ['ROLE_USER'],
                'password' => '123',
            ],
            [
                'email' => 'bar.bar@example.com',
                'roles' => ['ROLE_USER'],
                'password' => '123',
            ],
            [
                'email' => 'baz.baz@example.com',
                'roles' => ['ROLE_USER'],
                'password' => '123',
            ]
        ];

        foreach ($datas as $data) {
            $user = new User();

            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);
            $user->setPassword($this->hasher->hashPassword($user, $data['password']));

            $this->manager->persist($user);
        };

        for ($i = 0; $i < 100; $i++) {
            $user = new User();

            $user->setEmail($this->faker->email());
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->hasher->hashPassword($user, $this->faker->password(8,20)));

            $this->manager->persist($user);
        };

        $this->manager->flush();

    }

    public function loadEmprunteur(): void {

        $datas = [
            [
                'nom' => 'Foo',
                'prenom' => 'Foo',
                'tel' => '123456789',
                'createdAt' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s','2020-01-01 10:00:00'),
                'updatedAt' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s','2020-01-01 10:00:00'),
                'user' => 2,
            ],
            [
                'nom' => 'Bar',
                'prenom' => 'Bar',
                'tel' => '123456789',
                'createdAt' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 11:00:00'),
                'updatedAt' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-05-01 12:00:00'),
                'user' => 3,
            ],
            [
                'nom' => 'Baz',
                'prenom' => 'Baz',
                'tel' => '123456789',
                'createdAt' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-03-01 12:00:00'),
                'updatedAt' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 10:00:00'),
                'user' => 4,
            ]
        ];

        foreach ($datas as $data) {
            $emprunteur = new Emprunteur();
            $user = new User();

            $emprunteur->setNom($data['nom']);
            $emprunteur->setPrenom($data['prenom']);
            $emprunteur->setTel($data['tel']);
            $emprunteur->setCreatedAt($data['createdAt']);
            $emprunteur->setUpdatedAt($data['updatedAt']);
            $emprunteur->setUser($user->id === $data['user']);

            $this->manager->persist($emprunteur);
        };

        $this->manager->flush();
    }
}
