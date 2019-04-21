<?php

namespace Auth\Service;

use Auth\Entity\User;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Zend\Crypt\Password\Bcrypt;

class UserManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UserManager constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $data
     *
     * @return User
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(array $data): User
    {
        $user = new User;
        $bcrypt = new Bcrypt;

        $user->setName($data['name'])
             ->setEmail($data['email'])
             ->setPassword($bcrypt->create($data['password']))
             ->setCreatedAt(Carbon::now())
             ->setUpdatedAt(Carbon::now());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param User $user
     * @param array $data
     *
     * @return bool
     */
    public function checkCredentials(User $user, array $data): bool
    {
        $bcrypt = new Bcrypt;

        return $bcrypt->verify($data['password'], $user->getPassword());
    }
}
