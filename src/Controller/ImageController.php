<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\BestImage;
use App\Form\BestImageType;
use Symfony\Component\HttpFoundation\Request;

class ImageController extends AbstractController
{
    public function adminImage($page)
    {
		$nbPerPage = 18;

		$listAlbum = $this->getDoctrine()
		  ->getManager()
		  ->getRepository(BestImage::class)
		  ->getImages($page, $nbPerPage)
		;

		$nbPages = ceil(count($listAlbum) / $nbPerPage);

		return $this->render('pages/image/adminImage.html.twig', array(
		  'listAlbum'   => $listAlbum,
		  'nbPages'     => $nbPages,
		  'page'        => $page,
		));
	}
	
	public function add(Request $request)
    {
		$image = New BestImage();
		$form = $this->get('form.factory')->create(BestImageType::class, $image);
		
		
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			
			$request->getSession()->getFlashBag()->add('success', 'Image bien enregistrée.');

			$em->persist($image);
			$em->flush();
			
			return $this->redirectToRoute('admin_bestimage');
		}
        return $this->render('pages/image/add.html.twig', array(
            'form' => $form->createView(),
    ));
    }
	
	public function modify($id, Request $request)
    {
		$image = $this->getDoctrine()->getManager()->getRepository(BestImage::class)->find($id);
		$form = $this->get('form.factory')->create(BestImageType::class, $image);
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();

			$request->getSession()->getFlashBag()->add('notice', 'Album bien modifié.');
			
			$em->persist($image);
			$em->flush();

			return $this->redirectToRoute('admin_gallery');
		}
        return $this->render('pages/image/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
	
	public function delete($id, Request $request)
    {
		$image = $this->getDoctrine()->getManager()->getRepository(BestImage::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $request->getSession()->getFlashBag()->add('notice', 'Image a bien supriméeS.');
		$em->remove($image);
		$em->flush();
		
		return $this->redirectToRoute('admin_bestimage');
    }
}
