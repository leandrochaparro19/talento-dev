<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class DefaultController extends AbstractController
{
 
    #[Route(path: '/', name: 'homepage')]
    public function index(RouterInterface $router): Response
    {
        $routes = [];
        foreach (['proyecto_index', 'proyecto_create', 'buscador_desarrollador', /* etc... */] as $route) {
            $routes[$route] = $router->getRouteCollection()->get($route) !== null;
        }

        return $this->render('default/index.html.twig', [
            'routes' => $routes,
        ]);
    }
}
