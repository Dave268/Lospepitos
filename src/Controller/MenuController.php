<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Continent;
use App\Entity\Country;
use App\Entity\ArticleCategory;
use App\Entity\SubCategory;
use App\Form\ContinentType;
use App\Form\CountryType;
use App\Form\ArticleCategoryType;
use App\Form\SubCategoryType;
use Symfony\Component\Filesystem\Filesystem;
use App\Service\Navbar;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class MenuController extends AbstractController
{
	private function clearUpdate(KernelInterface $kernel){
		$em = $this->getDoctrine()->getManager();
		$menu = new Navbar($em);
		$filesystem = new Filesystem();
		$filesystem->dumpFile(__DIR__ . '/../../templates/core/navbar.html.twig', $menu->setNavbar());

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

    public function adminMenu()
    {
        $listCategory = $this->getDoctrine()
		  ->getManager()
		  ->getRepository(ArticleCategory::class)
		  ->findAll()
        ;

        $listSubCategory = $this->getDoctrine()
		  ->getManager()
		  ->getRepository(SubCategory::class)
		  ->findAll()
        ;

        $listContinent = $this->getDoctrine()
		  ->getManager()
		  ->getRepository(Continent::class)
		  ->findAll()
        ;

        $listCountry = $this->getDoctrine()
		  ->getManager()
		  ->getRepository(Country::class)
		  ->findAll()
        ;
        
        return $this->render('pages/menu/menu.html.twig', array(
            "listCategory"      => $listCategory,
            "listSubCategory"   => $listSubCategory,
            "listContinent"     => $listContinent,
            "listCountry"       => $listCountry,
        ));
    }

    public function addContinent(Request $request, KernelInterface $kernel)
    {
		$continent = New Continent();
		$form = $this->createForm(ContinentType::class, $continent, [
			'action' => $this->generateUrl('admin_articles_continent_add'),
			'method' => 'POST',
        ]);
		
		
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			
			
			
			$em->persist($continent);
            $em->flush();
			
			//update navbar + clear cache
			$this->clearUpdate($kernel);

			if($request->isXmlHttpRequest())
                    {
                        $json = json_encode(array(
                            'id'    => $continent->getId(),
							'name' 	=> $continent->getName(),
							'type'	=> 'Continent'
                        ));

                        $response = new Response($json);
                        $response->headers->set('Content-Type', 'application/json');

                        return $response;
					}

					$request->getSession()->getFlashBag()->add('success', 'Le continent est bien enregistré.');

			return $this->redirectToRoute('admin_menu');
			
		}
        return $this->render('pages/admin/form.html.twig', array(
      'form' => $form->createView(),
    ));
	}
	
	public function deleteContinent($id, Request $request, KernelInterface $kernel)
    {
		$continent = $this->getDoctrine()->getManager()->getRepository(Continent::class)->find($id);
		$em = $this->getDoctrine()->getManager();
		$em->remove($continent);
        $em->flush();
        
        //update navbar + clear cache
			$this->clearUpdate($kernel);
		$request->getSession()->getFlashBag()->add('success', 'Le continent est bien supprimé.');

		
		return $this->redirectToRoute('admin_menu');
	}

    public function modifyContinent($id, Request $request, KernelInterface $kernel)
    {
		$category = $this->getDoctrine()
        ->getManager()
        ->getRepository(Continent::class)
        ->find($id)
      ;;
		$form = $this->createForm(ContinentType::class, $category, [
			'method' => 'POST',
        ]);
		
		
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($category);
			$em->flush();
            
            //update navbar + clear cache
			$this->clearUpdate($kernel);
				$request->getSession()->getFlashBag()->add('success', 'Le Continent est bien modifié.');

			return $this->redirectToRoute('admin_menu');
			
		}
        return $this->render('pages/admin/form.html.twig', array(
      'form' => $form->createView(),
    ));
	}

	public function addCountry(Request $request, KernelInterface $kernel)
    {
		$country = New Country();
		$form = $this->createForm(CountryType::class, $country, [
			'action' => $this->generateUrl('admin_articles_country_add'),
			'method' => 'POST',
        ]);
		
		
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			
			
			$em->persist($country);
            $em->flush();
            //update navbar + clear cache
			$this->clearUpdate($kernel);

			if($request->isXmlHttpRequest())
                    {
                        $json = json_encode(array(
                            'id'    => $country->getId(),
							'name' 	=> $country->getName(),
							'type'	=> 'Country'
                        ));

                        $response = new Response($json);
                        $response->headers->set('Content-Type', 'application/json');

                        return $response;
					}

					$request->getSession()->getFlashBag()->add('success', 'Le pays est bien enregistré.');

			return $this->redirectToRoute('admin_menu');
			
		}
        return $this->render('pages/admin/form.html.twig', array(
      'form' => $form->createView(),
    ));
	}

    public function modifyCountry($id, Request $request, KernelInterface $kernel)
    {
		$category = $this->getDoctrine()
        ->getManager()
        ->getRepository(Country::class)
        ->find($id)
      ;;
		$form = $this->createForm(CountryType::class, $category, [
			'method' => 'POST',
        ]);
		
		
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($category);
			$em->flush();
            
            //update navbar + clear cache
			$this->clearUpdate($kernel);

				$request->getSession()->getFlashBag()->add('success', 'Le pays est bien modifié.');

			return $this->redirectToRoute('admin_menu');
			
		}
        return $this->render('pages/admin/form.html.twig', array(
      'form' => $form->createView(),
    ));
	}
	
	public function deleteCountry($id, Request $request, KernelInterface $kernel)
    {
		$country = $this->getDoctrine()->getManager()->getRepository(Country::class)->find($id);
		$em = $this->getDoctrine()->getManager();
		$em->remove($country);
		$em->flush();

        //update navbar + clear cache
			$this->clearUpdate($kernel);
		$request->getSession()->getFlashBag()->add('success', 'Le pays est bien supprimé.');

		
		return $this->redirectToRoute('admin_menu');
	}

	public function addCategory(Request $request, KernelInterface $kernel)
    {
		$category = New ArticleCategory();
		$form = $this->createForm(ArticleCategoryType::class, $category, [
			'action' => $this->generateUrl('admin_articles_category_add'),
			'method' => 'POST',
        ]);
		
		
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($category);
			$em->flush();
            
            //update navbar + clear cache
			$this->clearUpdate($kernel);

			if($request->isXmlHttpRequest())
                    {
                        $json = json_encode(array(
                            'id'    => $category->getId(),
							'name' 	=> $category->getName(),
							'type'	=> 'categories'
                        ));

                        $response = new Response($json);
                        $response->headers->set('Content-Type', 'application/json');

                        return $response;
					}
					
					$request->getSession()->getFlashBag()->add('success', 'La catégorie est bien enregistrée.');

			return $this->redirectToRoute('admin_menu');
			
		}
        return $this->render('pages/admin/form.html.twig', array(
      'form' => $form->createView(),
    ));
    }
    
    public function modifyCategory($id, Request $request, KernelInterface $kernel)
    {
		$category = $this->getDoctrine()
        ->getManager()
        ->getRepository(ArticleCategory::class)
        ->find($id)
      ;;
		$form = $this->createForm(ArticleCategoryType::class, $category, [
			'method' => 'POST',
        ]);
		
		
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($category);
			$em->flush();
            
            //update navbar + clear cache
			$this->clearUpdate($kernel);
			
				$request->getSession()->getFlashBag()->add('success', 'La catégorie est bien modifié.');

			return $this->redirectToRoute('admin_menu');
			
		}
        return $this->render('pages/admin/form.html.twig', array(
      'form' => $form->createView(),
    ));
	}
	
	public function deleteCategory($id, Request $request, KernelInterface $kernel)
    {
		$category = $this->getDoctrine()->getManager()->getRepository(ArticleCategory::class)->find($id);
		$em = $this->getDoctrine()->getManager();
		$em->remove($category);
		$em->flush();

		//update navbar + clear cache
		$this->clearUpdate($kernel);

		$request->getSession()->getFlashBag()->add('success', 'La catégorie est bien supprimée.');

		
		return $this->redirectToRoute('admin_menu');
	}

    public function addSubcategory(Request $request, KernelInterface $kernel)
    {
		$category = New SubCategory();
		$form = $this->createForm(SubCategoryType::class, $category, [
			'action' => $this->generateUrl('admin_subcategory_add'),
			'method' => 'POST',
        ]);
		
		
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($category);
            $em->flush();
            
            //update navbar + clear cache
			$this->clearUpdate($kernel);

			if($request->isXmlHttpRequest())
            {
                $json = json_encode(array(
                    'id'    => $category->getId(),
                    'name' 	=> $category->getName(),
                    'type'	=> 'categories'
                ));

                $response = new Response($json);
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            
            $request->getSession()->getFlashBag()->add('success', 'La sous-catégorie est bien enregistrée.');

			return $this->redirectToRoute('admin_menu');
			
		}
        return $this->render('pages/admin/form.html.twig', array(
      'form' => $form->createView(),
    ));
	}

    public function modifySubCategory($id, Request $request, KernelInterface $kernel)
    {
		$category = $this->getDoctrine()
        ->getManager()
        ->getRepository(SubCategory::class)
        ->find($id)
      ;;
		$form = $this->createForm(SubCategoryType::class, $category, [
			'method' => 'POST',
        ]);
		
		
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($category);
			$em->flush();
            
            //update navbar + clear cache
			$this->clearUpdate($kernel);

				$request->getSession()->getFlashBag()->add('success', 'La sous-catégorie est bien modifié.');

			return $this->redirectToRoute('admin_menu');
			
		}
        return $this->render('pages/admin/form.html.twig', array(
      'form' => $form->createView(),
    ));
	}
	
	public function deleteSubcategory($id, Request $request, KernelInterface $kernel)
    {
		$category = $this->getDoctrine()->getManager()->getRepository(SubCategory::class)->find($id);
		$em = $this->getDoctrine()->getManager();
		$em->remove($category);
		$em->flush();

		//update navbar + clear cache
			$this->clearUpdate($kernel);

		$request->getSession()->getFlashBag()->add('success', 'La catégorie est bien supprimée.');

		
		return $this->redirectToRoute('admin_menu');
	}
}
