<?php

namespace ApiBundle\Service;

use ApiBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;

/**
 * Class AuthenticationSuccessHandler
 * @package ApiBundle\Service
 */
class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    /** @var Registry */
    private $_registry;

    /**
     * AuthenticationSuccessHandler constructor.
     * @param HttpUtils $httpUtils
     * @param Registry $registry
     * @param array $options
     */
    public function __construct(HttpUtils $httpUtils, Registry $registry, array $options = array())
    {
        parent::__construct($httpUtils, $options);

        $this->_registry = $registry;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @return JsonResponse
     */
    function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        $user->setLastLogin(new \DateTime());
        $this->_registry->getManager()->persist($user);
        $this->_registry->getManager()->flush();

        return new JsonResponse([
            'id' => $user->getId(),
            'username' => $user->getUsername()
        ]);
    }
}