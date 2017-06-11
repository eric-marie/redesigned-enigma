<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\User;
use ApiBundle\ParentClasses\ParentController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\Security;

/**
 * Class SecurityController
 * @package ApiBundle\Controller
 *
 * @Route("/security")
 */
class SecurityController extends ParentController
{
    /**
     * Permet à l'utilisateur de s'inscrire
     *
     * @Route("/register/", name="api_security_register", options={"expose"=true})
     * @Method({"POST"})
     * @Template()
     *
     * @param Request $request
     * @return array|JsonResponse
     */
    public function registerAction(Request $request)
    {
        $requestParams = $this->_getJsonParams($request->getContent());

        if (!$this->_requestHasAll($requestParams, ['email', 'username', 'password', 'password2'])) {
            return $this->_getBadRequestJsonResponse(['Certains paramètres sont manquants.']);
        }

        $user = new User();
        $user->setEmail($requestParams['email']);
        $user->setUsername($requestParams['username']);
        $user->setPlainPassword($requestParams['password']);

        $errorResponse = $this->_validateNewUserParameters($user, $requestParams['password2']);
        if ($errorResponse != null) {
            return $errorResponse;
        }

        $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);

        $user->eraseCredentials();

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return ['user' => $user];
    }

    /**
     * Valide les informations envoyées pour l'inscription d'un nouvel utilisateur
     * Retourne null si c'est bon
     *          un JsonResponse contenant les erreurs si c'est pas bon
     *
     * @param User $user
     * @param $password2
     * @return null|JsonResponse
     */
    private function _validateNewUserParameters(User $user, $password2)
    {
        $validEntity = $this->_isValidEntity($user);
        if (!is_null($validEntity)) {
            return $validEntity;
        }

        $errors = [];

        // Password
        $securityLevel = 0;
        if (preg_match('/[a-z]/i', $user->getPlainPassword())) $securityLevel++;
        if (preg_match('/[A-Z]/i', $user->getPlainPassword())) $securityLevel++;
        if (preg_match('/[0-9]/i', $user->getPlainPassword())) $securityLevel++;
        if (preg_match('/[_\W]/i', $user->getPlainPassword())) $securityLevel++;
        if ($securityLevel < 3) {
            $errors[] = 'Le mot de passe doit contenir au moins 3 des éléments suivants : minuscule, majuscule, chiffre ou caractère spécial';
        } elseif ($user->getPlainPassword() != $password2) {
            $errors[] = 'Les deux mots de passe ne sont pas identiques';
        }

        if (0 == count($errors)) {
            return null;
        }

        return $this->_getBadRequestJsonResponse($errors);
    }

    /**
     * En cas d'erreur de login sur loginCheckAction, l'utilisateur est redirigé sur cette action
     * Elle sert donc à afficher les messages d'erreur
     *
     * @Route("/login/", name="api_security_login", options={"expose"=true})
     * @Method({"GET"})
     * @Template("@Api/Security/register.json.twig")
     *
     * @param Request $request
     * @return array|JsonResponse
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        }

        if ($error != null and is_object($error)) {
            if ($error instanceof BadCredentialsException) {
                $error = 'Identifiants incorrects';
            } elseif ($error instanceof AccountExpiredException) {
                $error = 'Votre compte a expiré';
            } elseif ($error instanceof LockedException) {
                $error = 'Votre compte a été bloqué';
            } elseif ($error instanceof DisabledException) {
                $error = 'Votre compte n\'est pas actif';
            }
        }

        if (!empty($error))
            return $this->_getBadRequestJsonResponse([$error], 401);

        return ['user' => $this->getUser()];
    }

    /**
     * Cette Action ne doit pas être complétée : tout est géré par Symfony
     *
     * @Route("/login-check/", name="api_security_login_check", options={"expose"=true})
     * @Method({"POST"})
     */
    public function loginCheckAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    /**
     * Cette Action ne doit pas être complétée : tout est géré par Symfony
     *
     * @Route("/logout/", name="api_security_logout", options={"expose"=true})
     * @Method({"GET"})
     */
    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }

    /**
     * Vérifie si l'utilisateur est connecté
     *
     * @Route("/is-logged-in/", name="api_security_is_logged_in", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function isLoggedInAction()
    {
        /** @var AuthorizationChecker $securityContext */
        $securityContext = $this->container->get('security.authorization_checker');
        $loggedIn = $securityContext->isGranted('IS_AUTHENTICATED_FULLY');

        return new JsonResponse(['loggedIn' => $loggedIn]);
    }
}