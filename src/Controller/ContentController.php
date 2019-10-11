<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Content;
use App\Form\ContentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContentController extends AbstractController
{

    public function admin($page)
    {
		$nbPerPage = 15;

		$listContent = $this->getDoctrine()
		  ->getManager()
		  ->getRepository(Content::class)
		  ->getContent($page, $nbPerPage)
		;

		$nbPages = ceil(count($listContent) / $nbPerPage);

		return $this->render('pages/content/admin.html.twig', array(
		  'listContent'    => $listContent,
		  'nbPages'         => $nbPages,
		  'page'            => $page,
		));
	}

    public function add(Request $request)
    {
		$content = New Content();
		$form = $this->get('form.factory')->create(ContentType::class, $content)->add('Publish', SubmitType::class);
		
		
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();

			$request->getSession()->getFlashBag()->add('success', 'Contenu bien enregistrée.');
			if ($form->getClickedButton() && 'Publish' === $form->getClickedButton()->getName()) {
				$request->getSession()->getFlashBag()->add('success', 'le contenu a bien été publié.');
				$content->setStatus('published');
			}
			
			$em->persist($content);
			$em->flush();
			
			return $this->redirectToRoute('admin_content');
		}
        return $this->render('pages/content/add.html.twig', array(
            'form' => $form->createView(),
    ));
    }
	
	public function modify($id, Request $request)
    {
		$content = $this->getDoctrine()->getManager()->getRepository(Content::class)->find($id);
		$form = $this->get('form.factory')->create(ContentType::class, $content);
		
		if($content->getStatus() == 'published')
		{
			$form->add('Draft', 	SubmitType::class);
		}
		else{
			$form->add('Publish', 	SubmitType::class);
		}
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			

			$request->getSession()->getFlashBag()->add('notice', 'Article bien modifié.');
			if ($form->getClickedButton() && 'Publish' === $form->getClickedButton()->getName()) {
					$request->getSession()->getFlashBag()->add('success', 'le contenu a bien été publié.');
					$content->setStatus('published');
			}
			else if ($form->getClickedButton() && 'Draft' === $form->getClickedButton()->getName()) {
					$request->getSession()->getFlashBag()->add('success', 'le contenu a bien été retiré des publications.');
					$content->setStatus('draft');

			}
			
			$em->persist($content);
			$em->flush();

			return $this->redirectToRoute('admin_content');
		}
        return $this->render('pages/content/add.html.twig', array(
            'form' => $form->createView(),
    ));
    }
	
	public function delete($id, Request $request)
    {
		$content = $this->getDoctrine()->getManager()->getRepository(Content::class)->find($id);
		$em = $this->getDoctrine()->getManager();
		$em->remove($content);
		$em->flush();
		
		$request->getSession()->getFlashBag()->add('success', 'Le contenu est bien supprimé.');


		return $this->redirectToRoute('admin_content');
	}
}
