<?php

namespace App\Service;

use App\Entity\Group;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param EntityManagerInterface
     * @param GroupRepository
     */
    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Group $group
     */
    public function getAllUsersByGroup(Group $group) : array
    {
        return $this->userRepository->findBy(['group' => $group->getId()]);
    }

    /**
     * @param int $id
     */
    public function getUserById(int $id) : ?User
    {
        return $this->userRepository->findOneBy(['id' => $id]);
    }

    /**
     * @param Group $group
     * @param User $user
     */
    public function saveUser(Group $group, User $user) : void
    {
        $user->setGroup($group);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param Group $group
     * @param User $userFromDb
     * @param User $userFromRequest
     */
    public function updateUser(Group $group, User &$userFromDb, User $userFromRequest) : void
    {
        $userFromDb->setName($userFromRequest->getName());
        $userFromDb->setEmail($userFromRequest->getEmail());
        $this->saveUser($group, $userFromDb);
    }

    /**
     * @param User
     */
    public function deleteUser(User $user) : void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}