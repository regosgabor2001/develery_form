<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FormType;
use App\Entity\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class FormController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'form')]
    public function index(Request $request): Response
    {
        $formEntity = new Form;

        $form = $this->createForm(FormType::class, $formEntity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($formEntity);
            $this->entityManager->flush();
                 
            $url = $this->generateUrl('form'); 

            $this->addFlash('Sikeres feltöltés!', 'Köszönjük szépen a kérdésedet. Válaszunkkal hamarosan keresünk a megadott e-mail címen.');
            return new RedirectResponse($url);
        }


        return $this->render('form/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
