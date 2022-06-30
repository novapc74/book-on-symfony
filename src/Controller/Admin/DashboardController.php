<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route(path:'/admin')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_secured_dashboard')]
    public function index(): Response
    {
        return $this->render('/admin/dashboard/dashboard.html.twig');
    }
}