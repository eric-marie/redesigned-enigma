<?php

namespace ApiBundle\ParentClasses;

use \Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ParentController
 * @package ApiBundle\ParentClasses
 */
class ParentController extends Controller
{
    /**
     * AngularJS envoi les données sous format JSON dans le contenu de la requête
     *
     * @param $requestContent
     * @return array|mixed
     */
    protected function _getJsonParams($requestContent)
    {
        $jsonParams = [];
        if (!empty($requestContent)) {
            $jsonParams = json_decode($requestContent, true);
        }

        return $jsonParams;
    }

    /**
     * Test si tous les paramètres requis sont fournis dans la requête
     *
     * @param array $requestParams
     * @param array $params
     * @return bool
     */
    protected function _requestHasAll(array $requestParams, array $params)
    {
        foreach ($params as $param) {
            if(!key_exists($param, $requestParams)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Retourne un message d'erreur au format JsonResponse
     *
     * @param array $errorMessages
     * @param int $errorCode
     * @return JsonResponse
     */
    protected function _getBadRequestJsonResponse(array $errorMessages = [], $errorCode = 400)
    {
        return new JsonResponse(['errorMessages' => $errorMessages], $errorCode);
    }

    /**
     * Verifie si une entité est valide :
     * - oui : le retour est null
     * - non : le retour est un JsonResponse d'erreur
     *
     * @param ParentEntity $entity
     * @return null|JsonResponse
     */
    protected function _isValidEntity(ParentEntity $entity)
    {
        $validator = $this->get('validator');
        $validatorErrors = $validator->validate($entity);

        if ($validatorErrors->count() > 0) {
            $errorMessages = [];

            if(!is_null($validatorErrors)) {
                foreach ($validatorErrors as $validatorError) {
                    $errorMessages[] = $validatorError->getMessage();
                }
            }

            return $this->_getBadRequestJsonResponse($errorMessages);
        }

        return null;
    }
}
