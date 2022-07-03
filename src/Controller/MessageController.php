<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\EditMessageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MessageRepository;
use VictorPrdh\ReCaptchaBundle\Form\ReCaptchaType;

class MessageController extends AbstractController
{
    #[Route(path: '/message/create', methods: ["GET", "POST"], name: 'message_create')]
    public function create(MessageRepository $messageRepository, Request $request): Response
    {
        $message = new Message();

        $form = $this->createForm(EditMessageFormType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageRepository->add($message, true);

            $this->addFlash(
                'success',
                'Message create successfuly!'
            );

            return $this->redirectToRoute('app_category');
        }

        return $this->render('/message/edit.html.twig', ['form' => $form->createView()]);
    }
}
