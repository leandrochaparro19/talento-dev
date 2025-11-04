<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AutenticacionController extends AbstractController
{
    #[Route(path: '/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(Request $request): Response
    {
        // Mock: conservar el email ingresado para mostrar en la vista
        $lastUsername = $request->request->get('email', '');

        // Si se envía el formulario simulamos un error/flash para prototipado
        if ($request->isMethod('POST')) {
            $this->addFlash('error', 'Credenciales inválidas (mock).');
        }

        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => null,
        ]);
    }

    #[Route(path: '/register', name: 'register', methods: ['GET', 'POST'])]
    public function register(Request $request): Response
    {
        // Datos mock para rellenar el formulario si se envía
        $formData = [
            'name'  => $request->request->get('name', ''),
            'email' => $request->request->get('email', ''),
        ];

        if ($request->isMethod('POST')) {
            // Simulamos creación y redirigimos al login
            $this->addFlash('success', 'Cuenta creada correctamente (mock).');
            return $this->redirectToRoute('login');
        }

        return $this->render('login/register.html.twig', $formData);
    }

    #[Route(path: '/restablecer_contraseña', name: 'restablecer_contraseña', methods: ['GET', 'POST'])]
    public function forgotPassword(Request $request): Response
    {
        $email = $request->request->get('email', '');

        if ($request->isMethod('POST')) {
            // Simulamos envío de email de recuperación
            $this->addFlash('success', 'Se envió el enlace de recuperación (mock).');
            return $this->redirectToRoute('login');
        }
        $token = 1;
        return $this->render('login/restablecer_contraseña.html.twig', [
            'email' => $email,
            'token' => $token
        ]);
    }

    #[Route(path: '/recuperar_contraseña/{token}', name: 'recuperar_contraseña', methods: ['GET', 'POST'])]
    public function resetPassword(Request $request, string $token): Response
    {
        // token disponible para la plantilla
        if ($request->isMethod('POST')) {
            // Simulamos restablecimiento y redirigimos al login
            $this->addFlash('success', 'Contraseña restablecida correctamente (mock).');
            return $this->redirectToRoute('login');
        }

        return $this->render('login/recuperar_contraseña.html.twig', [
            'token' => $token,
        ]);
    }
}
