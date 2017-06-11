<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Article;
use ApiBundle\ParentClasses\ParentController;
use ApiBundle\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ArticleController
 * @package ApiBundle\Controller
 *
 * @Route("/article")
 */
class ArticleController extends ParentController
{
    /**
     * Retourne la liste des Articles
     *
     * @Route("/", name="api_article_get_all", options={"expose"=true})
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER')")
     * @Template()
     *
     * @return array
     */
    public function getAllAction()
    {
        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->get('doctrine')->getRepository('ApiBundle:Article');
        $articles = $articleRepository->findAllWithComments();

        $this->get('doctrine.orm.entity_manager.abstract');

        return ['articles' => $articles];
    }

    /**
     * Retourne un Article trouvé par son Id
     *
     * @Route("/{id}/", name="api_article_get_one", requirements={"id": "\d+"}, options={"expose"=true})
     * @Method({"GET"})
     * @Security("has_role('ROLE_USER')")
     * @Template()
     *
     * @param $id integer
     * @return array|JsonResponse
     */
    public function getOneAction($id)
    {
        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->get('doctrine')->getRepository('ApiBundle:Article');
        $article = $articleRepository->findOneByIdWithComments($id);

        if(is_null($article)) {
            return $this->_getBadRequestJsonResponse(['L\'article demandé n\'existe pas.'], 404);
        }

        return ['article' => $article];
    }

    /**
     * Crée une Article et le retourne
     *
     * @Route("/", name="api_article_add", options={"expose"=true})
     * @Method({"POST"})
     * @Security("has_role('ROLE_USER')")
     * @Template("@Api/Article/getOne.json.twig")
     *
     * @param Request $request
     * @return array|JsonResponse
     */
    public function addAction(Request $request)
    {
        $requestParams = $this->_getJsonParams($request->getContent());

        if (!$this->_requestHasAll($requestParams, ['title', 'content'])) {
            return $this->_getBadRequestJsonResponse(['Certains paramètres sont manquants.']);
        }

        $newArticle = new Article();

        $newArticle->setTitle($requestParams['title']);
        $newArticle->setContent($requestParams['content']);

        $validEntity = $this->_isValidEntity($newArticle);
        if(!is_null($validEntity)) {
            return $validEntity;
        }

        $em = $this->get('doctrine')->getManager();
        $em->persist($newArticle);
        $em->flush();

        return ['article' => $newArticle];
    }

    /**
     * Met à jour un Article trouvé par son Id et le retourne
     *
     * @Route("/{id}/", name="api_article_update", requirements={"id": "\d+"}, options={"expose"=true})
     * @Method({"PUT"})
     * @ParamConverter("article", class="ApiBundle:Article")
     * @Security("(has_role('ROLE_USER') && user.getId() == article.getIdUser().getId()) || has_role('ROLE_SUPER_ADMIN')")
     * @Template("@Api/Article/getOne.json.twig")
     *
     * @param Request $request
     * @param $id integer
     * @return array|JsonResponse
     */
    public function updateAction(Request $request, $id)
    {
        $requestParams = $this->_getJsonParams($request->getContent());

        if (!$this->_requestHasAll($requestParams, ['title', 'content'])) {
            return $this->_getBadRequestJsonResponse(['Certains paramètres sont manquants.']);
        }

        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->get('doctrine')->getRepository('ApiBundle:Article');
        /** @var Article $article */
        $article = $articleRepository->findOneBy(['id' => $id]);

        if(is_null($article)) {
            return $this->_getBadRequestJsonResponse(['L\'article demandé n\'existe pas.'], 404);
        }

        $article->setTitle($requestParams['title']);
        $article->setContent($requestParams['content']);

        $validEntity = $this->_isValidEntity($article);
        if(!is_null($validEntity)) {
            return $validEntity;
        }

        $em = $this->get('doctrine')->getManager();
        $em->persist($article);
        $em->flush();

        return ['article' => $article];
    }

    /**
     * Supprime un Article trouvé par son Id
     *
     * @Route("/{id}/", name="api_article_delete", requirements={"id": "\d+"}, options={"expose"=true})
     * @Method({"DELETE"})
     * @ParamConverter("article", class="ApiBundle:Article")
     * @Security("(has_role('ROLE_USER') && user.getId() == article.getIdUser().getId()) || has_role('ROLE_SUPER_ADMIN')")
     *
     * @param $id integer
     * @return JsonResponse
     */
    public function deleteAction($id)
    {
        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->get('doctrine')->getRepository('ApiBundle:Article');
        /** @var Article $article */
        $article = $articleRepository->findOneBy(['id' => $id]);

        if(is_null($article)) {
            return $this->_getBadRequestJsonResponse(['L\'article demandé n\'existe pas.']);
        }

        $em = $this->get('doctrine')->getManager();
        $em->remove($article);
        $em->flush();

        return new JsonResponse();
    }
}
