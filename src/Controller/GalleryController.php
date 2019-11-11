<?php
// src/Controller/GalleryController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Album;
use App\Entity\Content;
use App\Form\AlbumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Finder\Finder;

class GalleryController extends AbstractController
{
    public function gallery($page)
    {
		$nbPerPage = 18;

		$listAlbum = $this->getDoctrine()
		  ->getManager()
		  ->getRepository(Album::class)
		  ->getPublishedAlbums($page, $nbPerPage)
		;

		$nbPages = ceil(count($listAlbum) / $nbPerPage);

		if ($page > $nbPages) {
		  // $this->createNotFoundException("La page ".$page." n'existe pas.");
		}

		$text = $this->getDoctrine()
			->getManager()
			->getRepository(Content::class)
			->findOneByType('gallery')
		;

		return $this->render('pages/gallery/gallery.html.twig', array(
		  'listAlbum'   => $listAlbum,
		  'nbPages'     => $nbPages,
		  'page'        => $page,
		  'text'		=> $text
		));
    }

    public function view($id)
    {
		$album = $this->getDoctrine()->getManager()->getRepository(Album::class)->find($id);
		$finder = new Finder();
		$finder->files()->in(__DIR__ . "/../../public" . $album->getUrl());
		$return = [];
		$url = $album->getUrl();
			foreach ($finder as $file) {
				$return[] = $url . "/" . $file->getRelativePathname();
			}
		
        return $this->render('pages/gallery/view.html.twig', array(
		'album' => $return,
		'title' => $album->getTitle(),
		'entity' => $album));
    }
	
	public function adminGallery($page)
    {
		$nbPerPage = 10;

		$listAlbum = $this->getDoctrine()
		  ->getManager()
		  ->getRepository(Album::class)
		  ->getAlbums($page, $nbPerPage)
		;

		$nbPages = ceil(count($listAlbum) / $nbPerPage);

		return $this->render('pages/gallery/admingallery.html.twig', array(
		  'listAlbum'   => $listAlbum,
		  'nbPages'     => $nbPages,
		  'page'        => $page,
		));
	}
	
	public function adminView($id)
    {
		$album = $this->getDoctrine()->getManager()->getRepository(Album::class)->find($id);
		$finder = new Finder();
		$finder->files()->in(__DIR__ . "/../../public" . $album->getUrl());
		$return = [];
		$url = $album->getUrl();
			foreach ($finder as $file) {
				$return[] = $url . "/" . $file->getRelativePathname();
			}
		
        return $this->render('pages/gallery/view.html.twig', array(
		'album' => $return,
		'title' => $album->getTitle()));
    }
	
	public function add(Request $request)
    {
		$album = New Album();
		$form = $this->get('form.factory')->create(AlbumType::class, $album)->add('Publish', SubmitType::class);
		
		
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			
			$request->getSession()->getFlashBag()->add('success', 'Album bien enregistrée.');
			$album->setUrl(dirname(rawurldecode($album->getUrlImg())));

			if ($form->getClickedButton() && 'Publish' === $form->getClickedButton()->getName()) {
				$request->getSession()->getFlashBag()->add('success', 'l\'album a bien été publié.');
				$album->setStatus('published');
			}

			$em->persist($album);
			$em->flush();
			
			return $this->redirectToRoute('admin_gallery');
		}
        return $this->render('pages/gallery/add.html.twig', array(
            'form' => $form->createView(),
    ));
    }
	
	public function modify($id, Request $request)
    {
		$album = $this->getDoctrine()->getManager()->getRepository(Album::class)->find($id);
		$form = $this->get('form.factory')->create(AlbumType::class, $album);

		if($album->getStatus() == 'published')
		{
			$form->add('Draft', 	SubmitType::class);
		}
		else{
			$form->add('Publish', 	SubmitType::class);
		}
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			

			$request->getSession()->getFlashBag()->add('notice', 'Album bien modifié.');
			if ($form->getClickedButton() && 'Publish' === $form->getClickedButton()->getName()) {
				$request->getSession()->getFlashBag()->add('success', 'l\'album a bien été publié.');
				$album->setStatus('published');
			}
			else if ($form->getClickedButton() && 'Draft' === $form->getClickedButton()->getName()) {
				$request->getSession()->getFlashBag()->add('success', 'l\'album a bien été retiré des publications.');
				$album->setStatus('draft');

			}
			
			$em->persist($album);
			$em->flush();

			return $this->redirectToRoute('admin_gallery');
		}
        return $this->render('pages/gallery/add.html.twig', array(
            'form' => $form->createView(),
    ));
    }
	
	public function delete($id, Request $request)
    {
		$album = $this->getDoctrine()->getManager()->getRepository(Album::class)->find($id);
		$em = $this->getDoctrine()->getManager();
		$em->remove($album);
		$em->flush();
		
		return $this->redirectToRoute('admin_gallery');
    }
}