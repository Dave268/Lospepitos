<?php
// src/Controller/CoreController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Article;
use App\Entity\BestImage;
use App\Entity\Content;

class CoreController extends AbstractController
{
    public function home()
    {
		$listArticles = $this->getDoctrine()
		  ->getManager()
		  ->getRepository(Article::class)
		  ->getPublishedArticles(1, 3, "Notre Projet Ecolo")
		;
		
		$listBlog = $this->getDoctrine()
		  ->getManager()
		  ->getRepository(Article::class)
		  ->getPublishedArticles(1, 3, "Journal De Voyage")
		;

		$listImage = $this->getDoctrine()
		  ->getManager()
		  ->getRepository(BestImage::class)
		  ->getPublishedImages(18)
		;

		$text = $this->getDoctrine()
			->getManager()
			->getRepository(Content::class)
			->findOneByType('accueil')
		;
        return $this->render('pages/home.html.twig', array(
		'listArticles' 	=> $listArticles,
		'listBlog'		=> $listBlog,
		'listImage'		=> $listImage,
		'text'			=> $text));
    }

	public function construction () {
		return $this->render('pages/construction.html.twig');
	}
}