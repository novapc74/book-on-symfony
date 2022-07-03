<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MessageRepository;

#[Route(path: '/admin')]
class MessageController extends AbstractController
{
    #[Route('/message', name: 'admin_message')]
    public function index(MessageRepository $messageRepository): Response
    {
        $messages = $messageRepository->findAll();

        return $this->render('/admin/message/index.html.twig', compact('messages'));
    }

    #[Route(path: '/message/edit/{id}', methods: ["GET", "POST"], name: 'admin_message_edit', requirements: ["id" => "^\d+"])]
    public function setDelivered(MessageRepository $messageRepository, $id): Response
    {
        $updatedMessage = $messageRepository->find($id);

        if (null === $updatedMessage) {
            throw $this->createNotFoundException(
                'Message for id: '. $id .' not found!'
            );
        }

        $updatedMessage->setDelivered(true);
        $messageRepository->add($updatedMessage, true);

        $this->addFlash(
            'success',
            'Message marked as delivered'
        );

        return $this->redirectToRoute('admin_message');
    }

    #[Route(path: '/message/delete/{id}', methods: ["GET", "POST"], name: 'admin_message_delete', requirements: ["id" => "^\d+"])]
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
            'success',
            'Message for id:' . $id . ' deleted successfuly!'
        );

        return $this->redirectToRoute('admin_message');
    }
}
