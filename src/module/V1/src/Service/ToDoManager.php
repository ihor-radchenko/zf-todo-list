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
     * @param ToDo $todo
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(ToDo $todo): void
    {
        $this->entityManager->remove($todo);

        $this->entityManager->flush();
    }

    /**
     * @param ToDo $todo
     * @param $data
     *
     * @return ToDo
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(ToDo $todo, $data): ToDo
    {
        $todo->setTitle($data['title'])
             ->setIsCompleted($data['is_completed'])
             ->setUpdatedAt(Carbon::now());

        $this->entityManager->flush();

        return $todo;
    }

    /**
     * @param $id
     * @param User $user
     *
     * @return object|null
     */
    public function checkExists($id, User $user): ?ToDo
    {
        return $this->entityManager->getRepository(ToDo::class)
                                   ->findOneBy(['id' => $id, 'user_id' => $user->getId()]);
    }

    /**
     * @param User $user
     * @param array $params
     *
     * @return array|object[]
     */
    public function getForUser(User $user, array $params): array
    {
        $filter = ['user_id' => $user->getId()];
        $sort = ['created_at' => 'DESC'];

        if (array_key_exists('sort', $params) && in_array($params['sort'], ['title', 'is_completed', 'created_at', 'updated_at'])) {
            $sort = $params['sort'][0] === '-' ? [substr($params['sort'], 1) => 'DESC'] : [$params['sort'] => 'ASC'];
        }

        if (array_key_exists('filter', $params) && is_array($params['filter']) && array_key_exists('is_completed', $params['filter'])) {

            $filter['is_completed'] = $params['filter']['is_completed'];
        }

        return $this->entityManager->getRepository(ToDo::class)
                                   ->findBy($filter, $sort);
    }
}
