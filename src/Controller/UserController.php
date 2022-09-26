<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserService;
use FOS\RestBundle\View\View;
use App\Entity\User;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use App\Exception\ExceptionFormatNormalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Group;

class UserController extends AbstractFOSRestController
{
    /**
     * @param UserService
     */
    private $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

     /**
     * @Route("api/groups/{id}/users", methods={"GET"})
     * 
     * @return View
     * @throws \Exception
     */
    public function getAllUsers(Group $group): View
    {
        return View::create($this->userService->getAllUsersByGroup($group), Response::HTTP_OK);
    }

    /**
     * @Route("api/users/{id}", methods={"GET"})
     * @ParamConverter("user", options={"id" = "id"})
     * 
     * @param User $user
     * 
     * @return View
     * @throws \Exception
     */
    public function getUserById(User $user): View
    {
        return View::create($user, Response::HTTP_OK);
    }

    /**
     * @Route("api/groups/{id}/users", methods={"POST"})
     * @ParamConverter("user", converter="fos_rest.request_body")
     * 
     * @param Group $group
     * @param User $user
     * @param ConstraintViolationListInterface $validationErrors
     * 
     * @return View
     * @throws \Exception
     */
    public function createUser(Group $group, User $user, ConstraintViolationListInterface $validationErrors): View
    {
        if (count($validationErrors) > 0) {
            return View::create(ExceptionFormatNormalizer::normalize($validationErrors), Response::HTTP_BAD_REQUEST);
        }
        $this->userService->saveUser($group, $user);
        return View::create($user, Response::HTTP_CREATED);
    }

    /**
     * @Route("api/groups/{group}/users/{userFromDb}", methods={"PUT"})
     * @ParamConverter("userFromRequest", converter="fos_rest.request_body")
     * 
     * @param Group $group
     * @param User $userFromDb
     * @param User $userFromRequest
     * @param ConstraintViolationListInterface $validationErrors
     * 
     * @return View
     * @throws \Exception
     */
    public function updateUser(Group $group, User $userFromDb, User $userFromRequest, ConstraintViolationListInterface $validationErrors): View
    {
        if (count($validationErrors) > 0) {
            return View::create(ExceptionFormatNormalizer::normalize($validationErrors), Response::HTTP_BAD_REQUEST);
        }
        $this->userService->updateUser($group, $userFromDb, $userFromRequest);
        return View::create($userFromDb, Response::HTTP_CREATED);
    }

     /**
     * @Route("api/users/{id}", methods={"DELETE"})
     * @ParamConverter("user", options={"id" = "id"})
     * 
     * @param User $user
     * 
     * @return View
     * @throws \Exception
     */
    public function deleteGroup(User $user): View
    {
        $this->userService->deleteUser($user);
        return View::create(array(), Response::HTTP_NO_CONTENT);
    }
}