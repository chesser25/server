<?php

namespace App\Service;

use App\Entity\Group;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class GroupService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * @param EntityManagerInterface
     * @param GroupRepository
     */
    public function __construct(EntityManagerInterface $entityManager, GroupRepository $groupRepository)
    {
        $this->entityManager = $entityManager;
        $this->groupRepository = $groupRepository;
    }

    public function getAllGroups() : array
    {
        return $this->groupRepository->findAll();
    }

    /**
     * @param int $id
     */
    public function getGroupById(int $id) : ?Group
    {
        return $this->groupRepository->findOneBy(['id' => $id]);
    }

    /**
     * @param Group
     */
    public function saveGroup(Group $group) : void
    {
        $this->entityManager->persist($group);
        $this->entityManager->flush();
    }

    /**
     * @param Group $groupFromDb
     * @param Group $groupFromRequest
     */
    public function updateGroup(Group &$groupFromDb, Group $groupFromRequest) : void
    {
        $groupFromDb->setName($groupFromRequest->getName());
        $this->saveGroup($groupFromDb);
    }

    /**
     * @param Group
     */
    public function deleteGroup(Group $group) : void
    {
        $this->entityManager->remove($group);
        $this->entityManager->flush();
    }
}