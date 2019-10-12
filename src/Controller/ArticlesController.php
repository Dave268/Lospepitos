<?php
// src/Controller/ArticlesController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Entity\Content;
use App\Entity\Continent;
use App\Entity\Country;
use App\Entity\ArticleCategory;
use App\Form\ArticleType;
use App\Form\ContinentType;
use App\Form\CountryType;
use App\Form\ArticleCategoryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Filesystem\Filesystem;
use App\Service\Navbar;

class ArticlesController extends AbstractController
{
    public function articles($category, $page)
    {
		$nbPerPage = 10;

		$listArticles = $this->getDoctrine()
		  ->getManager()
		  ->getRepository(Article::class)
		  ->getPublishedArticles($page, $nbPerPage, $category)
		;

		$nbPages = ceil(count($listArticles) / $nbPerPage);

		//if ($page > $nbPages) {
		  //throw $this->createNotFoundException("La page ".$page." n'existe pas.");
		//}
		$content = 'blog';

		if ($category == 1)
		{
			$content = 'article';
		}

		$text = $this->getDoctrine()
			->getManager()
			->getRepository(Content::class)
			->findOneByType($content)
		;

        return $this->render('pages/articles/articles.html.twig', array(
			'category'		=> $category,
		  	'listArticles' 	=> $listArticles,
		  	'nbPages'     	=> $nbPages,
		  	'page'        	=> $page,
			'text'			=> $text
		));
    }
	
	public function view($id)
    {
		$article = $this->getDoctrine()->getManager()->getRepository(Article::class)->find($id);
		
		if (null === $article) {
			throw new NotFoundHttpException("L'article n°".$id." n'existe pas.");
		}
		if($article->getStatus() == 'draft'){
			return $this->redirectToRoute('articles');
		}
		
        return $this->render('pages/articles/view.html.twig', array(
		'article' => $article));
    }
	
	public function adminArticles($page)
    {
		$nbPerPage = 10;

		$listArticles = $this->getDoctrine()
		  ->getManager()
		  ->getRepository(Article::class)
		  ->getArticles($page, $nbPerPage)
		;

		$nbPages = ceil(count($listArticles) / $nbPerPage);

		return $this->render('pages/articles/adminarticles.html.twig', array(
		  'listArticles' => $listArticles,
		  'nbPages'     => $nbPages,
		  'page'        => $page,
		));
	}
	
	public function adminView($id)
    {
		$article = $this->getDoctrine()->getManager()->getRepository(Article::class)->find($id);
		
		if (null === $article) {
			throw new NotFoundHttpException("L'article n°".$id." n'existe pas.");
		}
        return $this->render('pages/articles/view.html.twig', array(
		'article' => $article));
    }
	
	public function add(Request $request)
    {
		$article = New Article();

		
		$article->setTitle('Titre');
		$article->setText('Texte ici.');
		$article->setShort('Résumé ici.');
		$article->setUrl('Chemin vers image');
		$article->setAlt('Image');
		$article->setGps('-28.5333300751266, 28.129878797779497');

		$country = $this->getDoctrine()->getManager()->getRepository(Country::class)->find(1);
		$continent = $this->getDoctrine()->getManager()->getRepository(Continent::class)->find(1);
		$category = $this->getDoctrine()->getManager()->getRepository(ArticleCategory::class)->find(1);

		$article->setCountry($country);
		$article->setContinent($continent);
		$article->addCategory($category);

		$em = $this->getDoctrine()->getManager();
		
		$em->persist($article);
		$em->flush();

		//on met à jour la barre de navigation
		$menu = new Navbar($em);
		$filesystem = new Filesystem();
		$filesystem->dumpFile(__DIR__ . '../../../templates/core/navbar.html.twig', $menu->setNavbar());

		return $this->redirectToRoute('admin_articles_modify', array('id' => $article->getId()));
    }
	
	public function modify($id, Request $request)
    {
		$article = $this->getDoctrine()->getManager()->getRepository(Article::class)->find($id);
		$form = $this->get('form.factory')->create(ArticleType::class, $article, [
			'action' => $this->generateUrl('admin_articles_modify', array('id' => $article->getId())),
			'method' => 'POST',
        ]);
		
		if($article->getStatus() == 'published')
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
					$request->getSession()->getFlashBag()->add('success', 'l\'article a bien été publié.');
					$article->setStatus('published');
			}
			else if ($form->getClickedButton() && 'Draft' === $form->getClickedButton()->getName()) {
					$request->getSession()->getFlashBag()->add('success', 'l\'article a bien été retiré des publications.');
					$article->setStatus('draft');

			}
			
			$em->persist($article);
			$em->flush();

			//on met à jour la barre de navigation
            $menu = new Navbar($em);
			$filesystem = new Filesystem();
			$filesystem->dumpFile(__DIR__ . '../../../templates/core/navbar.html.twig', $menu->setNavbar());

			if($request->isXmlHttpRequest())
				{
					$json = json_encode(array(
						'id'    => $article->getId(),
					));

					$response = new Response($json);
					$response->headers->set('Content-Type', 'application/json');

					return $response;
				}

			return $this->redirectToRoute('admin_articles');
		}

        return $this->render('pages/articles/add.html.twig', array(
			  'form' => $form->createView(),
			  'articleID' => $id
    ));
    }
	
	public function delete($id, Request $request)
    {
		$article = $this->getDoctrine()->getManager()->getRepository(Article::class)->find($id);
		$em = $this->getDoctrine()->getManager();
		$em->remove($article);
		$em->flush();

		//on met à jour la barre de navigation
		$menu = new Navbar($em);
		$filesystem = new Filesystem();
		$filesystem->dumpFile(__DIR__ . '../../../templates/core/navbar.html.twig', $menu->setNavbar());
		
		$request->getSession()->getFlashBag()->add('success', 'L\'article est bien supprimé.');


		return $this->redirectToRoute('admin_articles');
	}
}