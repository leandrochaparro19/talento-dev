<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class PerfilController extends AbstractController
{
    #[Route('/perfil/{username}', name: 'perfil_show', methods: ['GET'])]
    public function showPerfil(string $username): Response
    {
        $perfil = [
            'id' => 1,
            'username' => $username,
            'nombre' => 'Leandro Chaparro',
            'rol' => 'desarrollador',
            'reputacion' => 4.8,
            'avatar_url' => '/assets/avatar-placeholder.svg',
            'descripcion' => 'Desarrollador Symfony. Me enfoco en backend limpio, performance y seguridad.',
            'skills' => ['PHP', 'Symfony', 'MySQL', 'Bootstrap'],
            'portfolio' => [
                ['nombre' => 'TalentoDev', 'url' => 'https://github.com/leandrochaparro19/talento-dev', 'tecnologias' => ['Symfony', 'Bootstrap']],
                ['nombre' => 'API interna', 'url' => '#', 'tecnologias' => ['PHP', 'REST']],
            ],
        ];

        return $this->render('perfil/show.html.twig', [
            'perfil' => $perfil,
        ]);
    }

    #[Route('/perfil/{username}/editar', name: 'perfil_edit', methods: ['GET','POST'])]
    public function editPerfil(Request $request, string $username): Response
    {
        if ($request->isMethod('POST')) {
            // MOCK: simulamos guardado
            $this->addFlash('success', 'Perfil actualizado (mock).');
            return $this->redirectToRoute('perfil_show', ['username' => $username]);
        }

     
        $user = [
            'username' => $username,
            'nombre' => 'Leandro Chaparro',
            'avatar_url' => '/assets/avatar-placeholder.svg',
            'descripcion' => 'Desarrollador Symfony. Me enfoco en backend limpio, performance y seguridad.',
            'skills' => ['PHP', 'Symfony', 'MySQL', 'Bootstrap'],
            'portfolio' => ['https://github.com/leandrochaparro19/talento-dev'],
        ];

        return $this->render('perfil/edit.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/perfil/{username}/eliminar', name: 'perfil_delete', methods: ['GET','POST'])]
    public function deletePerfil(Request $request, string $username): Response
    {
        if ($request->isMethod('POST')) {
            // MOCK: simulamos eliminación
            $this->addFlash('success', 'Cuenta eliminada (mock).');
            return $this->redirectToRoute('homepage');
        }

        //Ejemplos de alerrtas para mostrar en el sistema
        $this->addFlash(
            'success',
            '¡Tu perfil se ha actualizado correctamente!'
        );
        $this->addFlash(
            'info',
            'El sistema va a estar en mantenimiento a partir de las 14hs.'
        );
        $this->addFlash(
            'warning',
            'Tu foto de perfil no se pudo actualizar, pero tus otros datos sí se guardaron.'
        );
        $this->addFlash(
            'danger',
            'Error: No se pudo guardar el perfil. El nombre de usuario ya existe.'
        );
        return $this->render('perfil/delete_confirm.html.twig', [
            'username' => $username,
        ]);
    }

    #[Route('/perfil/{username}/avatar', name: 'perfil_upload_avatar', methods: ['GET','POST'])]
    public function uploadAvatar(Request $request, string $username): Response
    {
      
        if ($request->files->get('avatar')) {
            $this->addFlash('success', 'Avatar actualizado (mock).');
        } else {
            $this->addFlash('error', 'Seleccioná un archivo válido.');
        }

        return $this->redirectToRoute('perfil_edit', ['username' => $username]);
    }
}
