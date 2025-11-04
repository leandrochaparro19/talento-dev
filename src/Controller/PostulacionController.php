<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class PostulacionController extends AbstractController
{
    /** @return array<int, array<string,mixed>> */
    private function mockMisPostulaciones(): array
    {
        return [
            [
                'id' => 1001,
                'proyecto' => ['id' => 101, 'titulo' => 'Landing para e-commerce', 'tecnologias' => ['Symfony','Bootstrap']],
                'monto' => 750,
                'plazo' => 12,
                'mensaje' => 'Puedo entregar una primera versión en una semana.',
                'estado' => 'En revisión',
                'fecha' => '2025-10-22',
            ],
            [
                'id' => 1002,
                'proyecto' => ['id' => 102, 'titulo' => 'API REST para mobile', 'tecnologias' => ['PHP','Laravel']],
                'monto' => 1100,
                'plazo' => 20,
                'mensaje' => 'Experiencia integrando JWT y documentación con OpenAPI.',
                'estado' => 'Aceptada',
                'fecha' => '2025-10-24',
            ],
            [
                'id' => 1003,
                'proyecto' => ['id' => 103, 'titulo' => 'Módulo de pagos e integración', 'tecnologias' => ['Symfony','Docker']],
                'monto' => 1400,
                'plazo' => 28,
                'mensaje' => 'Integro Mercado Pago en sandbox y paso a prod.',
                'estado' => 'Rechazada',
                'fecha' => '2025-10-26',
            ],
        ];
    }

    /** @return array<int, array<string,mixed>> */
    private function mockRecibidas(): array
    {
        return [
            [
                'id' => 2001,
                'proyecto' => ['id' => 101, 'titulo' => 'Landing para e-commerce'],
                'desarrollador' => ['nombre' => 'María López', 'username' => 'mlopez'],
                'monto' => 800,
                'plazo' => 14,
                'mensaje' => 'Experta en Bootstrap y optimización Lighthouse.',
                'estado' => 'En revisión',
                'fecha' => '2025-10-27',
            ],
            [
                'id' => 2002,
                'proyecto' => ['id' => 101, 'titulo' => 'Landing para e-commerce'],
                'desarrollador' => ['nombre' => 'Juan Pérez', 'username' => 'jperez'],
                'monto' => 700,
                'plazo' => 10,
                'mensaje' => 'Puedo comenzar hoy mismo; portfolio adjunto.',
                'estado' => 'Enviada',
                'fecha' => '2025-10-28',
            ],
            [
                'id' => 2003,
                'proyecto' => ['id' => 103, 'titulo' => 'Módulo de pagos e integración'],
                'desarrollador' => ['nombre' => 'Ana Gómez', 'username' => 'agomez'],
                'monto' => 1500,
                'plazo' => 30,
                'mensaje' => 'Tengo implementaciones previas con MP y Webhooks.',
                'estado' => 'En revisión',
                'fecha' => '2025-10-29',
            ],
        ];
    }

    #[Route('/proyectos/{id}/postularse', name: 'postulacion_postularse', requirements: ['id' => '\d+'], methods: ['GET','POST'])]
    public function postularse(Request $request, int $id): Response
    {
        // Datos mínimos del proyecto (mock) para el formulario
        $proyecto = ['id' => $id, 'titulo' => "Proyecto #$id (mock)"];

        if ($request->isMethod('POST')) {
            // Guardado MOCK
            $this->addFlash('success', 'Postulación enviada (mock).');
            return $this->redirectToRoute('postulacion_mis');
        }

        return $this->render('postulacion/form.html.twig', [
            'proyecto' => $proyecto,
        ]);
    }

    // Dos rutas apuntan al mismo método: renderiza "mías" o "recibidas" según la ruta
    #[Route('/postulaciones/mias', name: 'postulacion_mis', methods: ['GET'])]
    #[Route('/postulaciones/recibidas', name: 'postulacion_recibidas', methods: ['GET'])]
    public function listarPostulaciones(Request $request): Response
    {
        $route = (string)$request->attributes->get('_route');

        if ($route === 'postulacion_recibidas') {
            return $this->render('postulacion/recibidas.html.twig', [
                'items' => $this->mockRecibidas(),
            ]);
        }

        // Por defecto: "mías"
        return $this->render('postulacion/mis_postulaciones.html.twig', [
            'items' => $this->mockMisPostulaciones(),
        ]);
    }

    #[Route('/postulaciones/{id}/aceptar', name: 'postulacion_aceptar', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function aceptarPostulacion(int $id): Response
    {
        // MOCK
        $this->addFlash('success', "Postulación #$id aceptada (mock).");
        return $this->redirectToRoute('postulacion_recibidas');
    }

    #[Route('/postulaciones/{id}/rechazar', name: 'postulacion_rechazar', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function rechazarPostulacion(int $id): Response
    {
        // MOCK
        $this->addFlash('success', "Postulación #$id rechazada (mock).");
        return $this->redirectToRoute('postulacion_recibidas');
    }
}
