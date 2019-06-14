<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

declare(strict_types=1);


namespace App\Controller;

use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CacheController
 * @package App\Controller
 *
 * @Route("/cache")
 */
class CacheController extends AbstractController
{
    /**
     * @return Response
     *
     * @throws Exception
     * @Route("/none", name="app_cache_none")
     */
    public function none()
    {
        $response = $this->render('cache/none.html.twig', [
            'date' => new DateTime(),
        ]);

        return $response;
    }

    /**
     * @return Response
     *
     * @throws Exception
     * @Route("/expiration/cache-control", name="app_cache_expiration_cache_control")
     */
    public function expirationCacheControl()
    {
        $response = $this->render('cache/expiration-cache-control.html.twig', [
            'date' => new DateTime(),
        ]);

        $response->setSharedMaxAge(10);

        return $response;
    }

    /**
     * @return Response
     *
     * @throws Exception
     * @Route("/expiration/expires", name="app_cache_expiration_expires")
     */
    public function expirationExpires()
    {
        $date = new DateTime();

        $response = $this->render('cache/expiration-expires.html.twig', [
            'date' => $date,
        ]);

        $date->modify('+10 seconds');
        $response->setExpires($date);

        return $response;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/validation/etag", name="app_cache_validation_etag")
     */
    public function validationEtag(Request $request)
    {
        $response = $this->render('cache/validation-etag.html.twig');
        $response->setEtag(md5($response->getContent()));
        $response->isNotModified($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @throws Exception
     * @Route("/validation/last-modified", name="app_cache_validation_last_modified")
     */
    public function validationLastModified(Request $request)
    {
        $dateLastModified = new DateTime('2019-06-01T00:00:00Z');

        $response = new Response();
        $response->setLastModified($dateLastModified);

        if ($response->isNotModified($request)) {
            return $response;
        }

        $response->setContent($this->renderView('cache/validation-last-modified.html.twig'));
        return $response;
    }
}