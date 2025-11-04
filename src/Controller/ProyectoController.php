<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class ProyectoController extends AbstractController
{
    /** @return array<int, array<string,mixed>> */
    private function mockProyectos(): array
    {
        return [
            [
                'id' => 101,
                'titulo' => 'Landing para e-commerce',
                'descripcion' => 'Se requiere landing responsive con catálogo básico y formulario de contacto.',
                'tecnologias' => ['Symfony', 'Bootstrap', 'MySQL'],
                'estado' => 'Publicado',
                'presupuesto' => 800,
                'plazo' => 14,
                'cliente' => 'Acme SRL',
                'creado' => '2025-10-10',
            ],
            [
                'id' => 102,
                'titulo' => 'API REST para mobile',
                'descripcion' => 'Diseño e implementación de API REST con autenticación y documentación.',
                'tecnologias' => ['PHP', 'Laravel', 'PostgreSQL'],
                'estado' => 'Publicado',
                'presupuesto' => 1200,
                'plazo' => 21,
                'cliente' => 'StartUp XYZ',
                'creado' => '2025-10-18',
            ],
            [
                'id' => 103,
                'titulo' => 'Módulo de pagos e integración',
                'descripcion' => 'Integrar pasarela de pagos y registrar transacciones.',
                'tecnologias' => ['Symfony', 'Docker', 'MySQL'],
                'estado' => 'Pausado',
                'presupuesto' => 1500,
                'plazo' => 30,
                'cliente' => 'Retail SA',
                'creado' => '2025-10-20',
            ],
            [
                'id' => 104,
                'titulo' => 'Panel de administración',
                'descripcion' => 'Backoffice para administrar usuarios, proyectos y métricas.',
                'tecnologias' => ['React', 'Node', 'MongoDB'],
                'estado' => 'Borrador',
                'presupuesto' => 1800,
                'plazo' => 25,
                'cliente' => 'InHouse',
                'creado' => '2025-10-25',
            ],
        ];
    }

    #[Route('/proyectos', name: 'proyecto_index', methods: ['GET'])]
    public function listProyectos(Request $request): Response
    {
        $q = (string) $request->query->get('q', '');
        $tec = (array) $request->query->all('tecnologias'); // multiple
        $estado = (string) $request->query->get('estado', '');
        $min = (int) $request->query->get('presupuesto_min', 0);
        $max = (int) $request->query->get('presupuesto_max', 0);

        $items = $this->mockProyectos();

        // Filtrado básico (mock)
        $filtered = array_filter($items, function ($p) use ($q, $tec, $estado, $min, $max) {
            if ($q !== '' && stripos($p['titulo'].' '.$p['descripcion'], $q) === false) return false;
            if ($estado !== '' && $p['estado'] !== $estado) return false;
            if (!empty($tec)) {
                // al menos una tecnología coincidente
                if (!array_intersect(array_map('strtolower', $tec), array_map('strtolower', $p['tecnologias']))) {
                    return false;
                }
            }
            if ($min > 0 && $p['presupuesto'] < $min) return false;
            if ($max > 0 && $p['presupuesto'] > $max) return false;
            return true;
        });

        return $this->render('proyecto/index.html.twig', [
            'q' => $q,
            'tecnologias' => $tec,
            'estado' => $estado,
            'presupuesto_min' => $min ?: '',
            'presupuesto_max' => $max ?: '',
            'proyectos' => $filtered,
        ]);
    }

    #[Route('/proyectos/buscar', name: 'proyecto_search', methods: ['GET'])]
    public function searchProyectos(Request $request): Response
    {
        // Para prototipo, reutilizamos el index con los mismos filtros
        return $this->listProyectos($request);
    }

    #[Route('/proyectos/{id}', name: 'proyecto_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showProyecto(int $id): Response
    {
        $proyecto = null;
        foreach ($this->mockProyectos() as $p) {
            if ($p['id'] === $id) { $proyecto = $p; break; }
        }
        if (!$proyecto) {
            $this->addFlash('error', 'Proyecto no encontrado (mock).');
            return $this->redirectToRoute('proyecto_index');
        }

        return $this->render('proyecto/show.html.twig', [
            'proyecto' => $proyecto,
        ]);
    }

    #[Route('/proyectos/nuevo', name: 'proyecto_create', methods: ['GET','POST'])]
    public function createProyecto(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            // MOCK: simulamos creación OK
            $this->addFlash('success', 'Proyecto creado (mock).');
            return $this->redirectToRoute('proyecto_index');
        }

        return $this->render('proyecto/create.html.twig', [
            'defaults' => [
                'titulo' => '',
                'descripcion' => '',
                'tecnologias' => [],
                'presupuesto' => '',
                'plazo' => '',
            ]
        ]);
    }

    #[Route('/proyectos/{id}/editar', name: 'proyecto_edit', requirements: ['id' => '\d+'], methods: ['GET','POST'])]
    public function editProyecto(Request $request, int $id): Response
    {
        // MOCK: datos “existentes”
        $existente = [
            'id' => $id,
            'titulo' => 'Landing para e-commerce',
            'descripcion' => 'Se requiere landing responsive con catálogo.',
            'tecnologias' => ['Symfony', 'Bootstrap', 'MySQL'],
            'presupuesto' => 800,
            'plazo' => 14,
            'estado' => 'Publicado',
        ];

        if ($request->isMethod('POST')) {
            $this->addFlash('success', 'Proyecto actualizado (mock).');
            return $this->redirectToRoute('proyecto_show', ['id' => $id]);
        }

        return $this->render('proyecto/edit.html.twig', [
            'proyecto' => $existente,
        ]);
    }

    #[Route('/proyectos/{id}/eliminar', name: 'proyecto_delete', requirements: ['id' => '\d+'], methods: ['GET','POST'])]
    public function deleteProyecto(Request $request, int $id): Response
    {
        if ($request->isMethod('POST')) {
            $this->addFlash('success', "Proyecto #$id eliminado (mock).");
            return $this->redirectToRoute('proyecto_index');
        }

        return $this->render('proyecto/delete_confirm.html.twig', [
            'id' => $id,
        ]);
    }

    #[Route('/proyectos/{id}/pausar', name: 'proyecto_pause', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function pauseProyecto(int $id): Response
    {
        $this->addFlash('success', "Proyecto #$id pausado (mock).");
        return $this->redirectToRoute('proyecto_show', ['id' => $id]);
    }
}
