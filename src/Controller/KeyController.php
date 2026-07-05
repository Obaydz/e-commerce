<?php

namespace App\Controller;

use App\Entity\Key;
use App\Form\KeyType;
use App\Repository\KeyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/key')]
final class KeyController extends AbstractController
{
    #[Route(name: 'app_key_index', methods: ['GET'])]
    public function index(KeyRepository $keyRepository): Response
    {
        return $this->render('key/index.html.twig', [
            'keys' => $keyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_key_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $key = new Key();
        $form = $this->createForm(KeyType::class, $key);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($key);
            $entityManager->flush();

            return $this->redirectToRoute('app_key_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('key/new.html.twig', [
            'key' => $key,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_key_show', methods: ['GET'])]
    public function show(Key $key): Response
    {
        return $this->render('key/show.html.twig', [
            'key' => $key,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_key_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Key $key, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(KeyType::class, $key);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_key_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('key/edit.html.twig', [
            'key' => $key,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_key_delete', methods: ['POST'])]
    public function delete(Request $request, Key $key, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$key->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($key);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_key_index', [], Response::HTTP_SEE_OTHER);
    }
}
