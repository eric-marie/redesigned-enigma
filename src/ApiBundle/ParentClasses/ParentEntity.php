<?php

namespace ApiBundle\ParentClasses;

/**
 * La classe à étendre pour toutes les entitées
 *
 * Class ParentEntity
 * @package ApiBundle\ParentClasses
 */
class ParentEntity
{
    /**
     * Renvoi le tableau associatif représentant l'entité pour la réponse en JSON l'API
     *
     * @return array
     */
    public function getJsonArray()
    {
        return [];
    }
}