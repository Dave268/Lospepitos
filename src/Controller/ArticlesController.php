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
use App\Entity\SubCategory;
use App\Form\ArticleType;
use App\Form\ContinentType;
use App\Form\CountryType;
use App\Form\ArticleCategoryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Filesystem\Filesystem;
use App\Service\Navbar;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;


class ArticlesController extends AbstractController
{
	private function clearUpdate(KernelInterface $kernel){
		$em = $this->getDoctrine()->getManager();
		$menu = new Navbar($em);
		$filesystem = new Filesystem();
		$filesystem->dumpFile(__DIR__ . '../../../templates/core/navbar.html.twig', $menu->setNavbar());

			$application = new Application($kernel);
			$application->setAutoExit(false);
	
			$input = new ArrayInput([
				'command' => 'cache:clear',
				'--env' => 'prod',
				'--no-warmup' => true,
			]);
	
			// You can use NullOutput() if you don't need the output
			$application->run($input, new NullOutput());

	
			// return new Response(""), if you used NullOutput()
			return new Response("");
	}

    public function articles($category, $type, $sub, $page)
    {
		$nbPerPage = 10;

		$listArticles = $this->getDoctrine()
		  ->getManager()
		  ->getRepository(Article::class)
		  ->getPublishedArticles($page, $nbPerPage, $category, $type, $sub)
		;

		$nbPages = ceil(count($listArticles) / $nbPerPage);

		$categoryEntity = $this->getDoctrine()->getManager()->getRepository(ArticleCategory::class)->findOneByName($category);

		$text = $this->getDoctrine()
			->getManager()
			->getRepository(Content::class)
			->findOneByType($category)
		;

        return $this->render('pages/articles/articles.html.twig', array(
			'category'		=> $categoryEntity,
		  	'listArticles' 	=> $listArticles,
		  	'nbPages'     	=> $nbPages,
		  	'page'        	=> $page,
			'text'			=> $text,
			'type'			=> $type,
			'sub'			=> $sub
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
	
	public function add(Request $request, KernelInterface $kernel)
    {
		$article = New Article();

		
		$article->setTitle('Titre');
		$article->setText('Texte ici.');
		$article->setShort('Résumé ici.');
		$article->setUrl('Chemin vers image');
		$article->setAlt('Image');
		$article->setGps('-28.5333300751266, 28.129878797779497');

		$em = $this->getDoctrine()->getManager();
		
		$em->persist($article);
		$em->flush();

		//update navbar + clear cache
		$this->clearUpdate($kernel);
		return $this->redirectToRoute('admin_articles_modify', array('id' => $article->getId()));
    }
	
	public function modify($id, Request $request, KernelInterface $kernel)
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

			//update navbar + clear cache
			$this->clearUpdate($kernel);

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
	
	public function delete($id, Request $request, KernelInterface $kernel)
    {
		$article = $this->getDoctrine()->getManager()->getRepository(Article::class)->find($id);
		$em = $this->getDoctrine()->getManager();
		$em->remove($article);
		$em->flush();

		//update navbar + clear cache
			$this->clearUpdate($kernel);

		$request->getSession()->getFlashBag()->add('success', 'L\'article est bien supprimé.');


		return $this->redirectToRoute('admin_articles');
	}
}