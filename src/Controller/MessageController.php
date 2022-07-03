<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\EditMessageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MessageRepository;

class MessageController extends AbstractController
{
    #[Route('/message', name: 'app_message')]
    public function index(MessageRepository $messageRepository): Response
    {
        $messages = $messageRepository->findAll();

        return $this->render('message/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route(path: '/message/create', methods: ["GET", "POST"], name: 'app_create_message')]
    public function create(MessageRepository $messageRepository, Request $request): Response
    {
        $message = new Message();
        $form = $this->createForm(EditMessageFormType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageRepository->add($message, true);

            $this->addFlash('success', 'Message create successfuly!');

            return $this->redirectToRoute('app_message');
        }

        return $this->render('/message/edit.html.twig', ['form' => $form->createView()]);
    }

    #[Route(path: '/message/delete/{id}', methods: ["GET", "POST"], name: 'message_delete', requirements: ["id" => "^\d+"])]
    public function delete(MessageRepository $messageRepository, $id): Response
    {
        $deletedMessage = $messageRepository->find($id);

        if (null === $deletedMessage) {
            
            throw $this->createNotFoundException(
                'Message whith id:' . $id . ' not found!'
            );
        }

        $messageRepository->remove($deletedMessage, true);
        $this->addFlash(
            'success', 'Message for id:' . $id . ' deleted successfuly!'
        );

        return $this->redirectToRoute('app_message');
    }
}
