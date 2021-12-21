<?php

namespace App\Controller;

use App\Entity\Sprinkler;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SprinklerController extends AbstractController
{
    /**
     * @Route("/sprinklers", methods={"GET"})
     */
    public function list(ManagerRegistry $doctrine): Response
    {
        $sprinklers = $doctrine->getRepository(Sprinkler::class)->findAll();
        return new JsonResponse($sprinklers);
    }

    /**
     * @Route("/sprinkler/{id}", methods={"GET"})
     */
    public function get(ManagerRegistry $doctrine, int $id): Response
    {
        $sprinkler = $doctrine->getRepository(Sprinkler::class)->find($id);

        if (!$sprinkler) {
            return $this->notFoundJsonResponse($id);
        }

        return new JsonResponse($sprinkler);
    }

    /**
     * @Route("/sprinkler/{id}/instruction", methods={"GET"})
     */
    public function instruction(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $sprinkler = $doctrine->getRepository(Sprinkler::class)->find($id);

        if (!$sprinkler) {
            return $this->notFoundJsonResponse($id);
        }

        $instruction = '';

        switch ($sprinkler->getState()) {
            case 'turning_on':
                $sprinkler->setState('on');
                // $entityManager->persist($sprinkler);
                $instruction = 'turn_on';
                break;
            case 'turning_off':
                $sprinkler->setState('off');
                // $entityManager->persist($sprinkler);
                $instruction = 'turn_off';
                break;
            case 'on':
            case 'off':
                $instruction = 'stay_' . $sprinkler->getState();
                break;
            default:
                $instruction = 'error';
        }

        $entityManager->flush();

        return new JsonResponse(['instruction' => $instruction]);
    }


    /**
     * @Route("/sprinkler", methods={"POST"})
     */
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        $payload = json_decode($request->getContent());

        $sprinkler = new Sprinkler();
        $sprinkler->setName($payload->name);
        $sprinkler->setState($payload->state ?? 'off');

        $entityManager->persist($sprinkler);
        $entityManager->flush();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Created sprinkler ' . $sprinkler->getId(),
            'sprinkler' => $sprinkler
        ]);
    }


    /**
     * @Route("/sprinkler/{id}", methods={"PUT"})
     */
    public function update(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $sprinkler = $entityManager->getRepository(Sprinkler::class)->find($id);

        $payload = json_decode($request->getContent());

        if (!$sprinkler) {
            return $this->notFoundJsonResponse($id);
        }

        $sprinkler->setName($payload->name ?? $sprinkler->getName());
        $sprinkler->setState($payload->state ?? $sprinkler->getState());
        $entityManager->flush();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Updated sprinkler ' . $sprinkler->getId(),
            'sprinkler' => $sprinkler
        ]);
    }

    /**
     * @Route("/sprinkler/{id}", methods={"DELETE"})
     */
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $sprinkler = $doctrine->getRepository(Sprinkler::class)->find($id);

        if (!$sprinkler) {
            return $this->notFoundJsonResponse($id);
        }

        $entityManager->remove($sprinkler);
        $entityManager->flush();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Removed sprinkler ' . $sprinkler->getId(),
            'sprinkler' => $sprinkler
        ]);
    }

    private static function notFoundJsonResponse(int $id): JsonResponse
    {
        return new JsonResponse([
            'status' => 'error',
            'message' => "Sprinkler ${id} not found"
        ], 404);
    }
}
