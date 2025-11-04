<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class SkillController extends AbstractController
{
    #[Route('/perfil/{username}/skills/add', name: 'skill_add', methods: ['GET','POST'])]
    public function addSkill(Request $request, string $username): Response
    {
        
        $skill = trim((string)$request->request->get('skill', ''));
        $this->addFlash('success', $skill ? "Skill «{$skill}» agregada (mock)." : 'Ingresá un skill.');
        return $this->redirectToRoute('perfil_edit', ['username' => $username]);
    }

    #[Route('/perfil/{username}/skills/{skill}/remove', name: 'skill_remove', methods: ['POST'])]
    public function removeSkill(string $username, string $skill): Response
    {
      
        $this->addFlash('success', "Skill «{$skill}» eliminada (mock).");
        return $this->redirectToRoute('perfil_edit', ['username' => $username]);
    }

    #[Route('/perfil/{username}/skills/update', name: 'skill_update', methods: ['GET','POST'])]
    public function updateSkill(Request $request, string $username): Response
    {
        $csv = (string)$request->request->get('skills', '');
        $this->addFlash('success', 'Skills actualizadas (mock).');
        return $this->redirectToRoute('perfil_edit', ['username' => $username]);
    }
}
