<?php

namespace V1\Service;

use Auth\Entity\User;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use V1\Entity\ToDo;

class ToDoManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * ToDoManager constructor.
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
     * @return ToDo
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(array $data): ToDo
    {
        $todo = new ToDo;

        $todo->setTitle($data['title'])
             ->setUserId($data['user_id'])
             ->setIsCompleted(false)
             ->setCreatedAt(Carbon::now())
             ->setUpdatedAt(Carbon::now());

        $this->entityManager->persist($todo);
        $this->entityManager->flush();

        return $todo;
    }

    /**
     * @param User $user
     *
     * @return array|object[]
     */
    public function getForUser(User $user): array
    {
        return $this->entityManager->getRepository(ToDo::class)
                                   ->findBy(['user_id' => $user->getId()], ['created_at' => 'DESC']);
    }
}
