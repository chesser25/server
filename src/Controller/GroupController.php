<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Service\GroupService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use App\Entity\Group;
use App\Exception\ExceptionFormatNormalizer;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class GroupController extends AbstractFOSRestController
{
    private $groupService;
    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

     /**
     * @Route("api/groups", methods={"GET"})
     * 
     * @return View
     * @throws \Exception
     */

    public function getAllGroups(): View
    {
        return View::create($this->groupService->getAllGroups(), Response::HTTP_OK);
    }

    /**
     * @Route("api/groups/{id}", methods={"GET"})
     * @ParamConverter("group", options={"id" = "id"})
     * 
     * @param Group $group
     * 
     * @return View
     * @throws \Exception
     */
    public function getGroup(Group $group): View
    {
        return View::create($group, Response::HTTP_OK);
    }

    /**
     * @Route("api/groups", methods={"POST"})
     * @ParamConverter("group", converter="fos_rest.request_body")
     * 
     * @param Group $group
     * @param ConstraintViolationListInterface $validationErrors
     * 
     * @return View
     * @throws \Exception
     */
    public function createGroup(Group $group, ConstraintViolationListInterface $validationErrors): View
    {
        if (count($validationErrors) > 0) {
            return View::create(ExceptionFormatNormalizer::normalize($validationErrors), Response::HTTP_BAD_REQUEST);
        }
        $this->groupService->saveGroup($group);
        return View::create($group, Response::HTTP_CREATED);
    }

    /**
     * @Route("api/groups/{id}", methods={"PUT"})
     * @ParamConverter("groupFromRequest", converter="fos_rest.request_body")
     * 
     * @param Group $groupFromDb
     * @param Group $groupFromRequest
     * @param ConstraintViolationListInterface $validationErrors
     * 
     * @return View
     * @throws \Exception
     */
    public function updateGroup(Group $groupFromDb, Group $groupFromRequest, ConstraintViolationListInterface $validationErrors): View
    {
        if (count($validationErrors) > 0) {
            return View::create(ExceptionFormatNormalizer::normalize($validationErrors), Response::HTTP_BAD_REQUEST);
        }
        $this->groupService->updateGroup($groupFromDb, $groupFromRequest);
        return View::create($groupFromDb, Response::HTTP_CREATED);
    }

     /**
     * @Route("api/groups/{id}", methods={"DELETE"})
     * @ParamConverter("group", options={"id" = "id"})
     * 
     * @param Group $group
     * 
     * @return View
     * @throws \Exception
     */
    public function deleteGroup(Group $group): View
    {
        $this->groupService->deleteGroup($group);
        return View::create(array(), Response::HTTP_NO_CONTENT);
    }
}
