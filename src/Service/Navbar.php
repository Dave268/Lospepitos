<?php
// src/Service/Navbar.php
namespace App\Service;

use Doctrine\ORM\EntityManager;
use App\Entity\Continent;
use App\Entity\Country;
use App\Entity\ArticleCategory;
use App\Entity\SubCategory;

class Navbar
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setNavbar()
    {
        $categories = $this->em->getRepository(ArticleCategory::class)
        ->findAll();

        $content = "<div>";
        $content .= '<nav class="navbar navbar-light navbar-expand-xl sticky-top navigation-clean-button" style="height: 120px;background-color: rgb(255,252,243);">';
        $content .= '<div class="container-fluid"><a class="navbar-brand" href="{{ path(\'home\') }}" style="padding-top: 0px;">&nbsp;<img class="img-fluid d-none d-xl-inline-block" src="{{ asset(\'assets/img/LOGO2004_v6_LG.png\') }}" style="height: 100%;margin-top: 150px;"><img class="img-fluid d-none d-lg-inline-block d-xl-none" src="{{ asset(\'assets/img/LOGO2004_v6_LG.png\') }}" style="height: 80%;margin-top: -50px;"><img class="img-fluid d-inline-block d-md-none" src="{{ asset(\'assets/img/LOGO2004_v6_SM.png\') }}" style="margin-top: 30px;"><img class="d-none d-sm-none d-md-inline-block d-lg-none" src="{{ asset(\'assets/img/LOGO2004_v6_MD.png\') }}" style="margin-top: 0px;"></a>';
        $content .= '<button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>';
        $content .= '<div class="collapse navbar-collapse" id="navcol-1" style="background-color: rgb(255,252,243);height: 100%; z-index:1000;">';
        $content .= '<ul class="nav navbar-nav ml-auto" style="margin-bottom: 0px;color: rgb(0,0,0);background-color: rgb(255,252,243);">';
        $content .= '<li class="nav-item" role="presentation"><a class="nav-link {% if app.request.get(\'_route\') == \'home\' %}active{% endif %}" href="{{ path(\'home\') }}"><i class="fa fa-home" style="font-size: 17px"></i>&nbsp;</a></li>';
        $content .= '<li class="nav-item" role="presentation"><a class="nav-link {% if app.request.get(\'_route\') == \'about\' %}active{% endif %}" href="{{ path(\'about\') }}"><i class="far fa-smile-beam" style="font-size: 17px"></i>&nbsp; Nous</a></li>';

        foreach ($categories as $a)
        {   
            $content .= '<li class="nav-item dropdown">';
            $content .= '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $a->getFont() . '&nbsp; ' . $a->getName() . '</a>';
            $content .= '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';

            $subcategories = $this->em->getRepository(SubCategory::class)->findBy(['category' => $a]);
            $tempSub = false;
            foreach ($subcategories as $sub){
                if(!$sub->getArticles()->isEmpty()){
                    $content .= '<a class="dropdown-item" href="/articles/list/' . $a->getName() . '/sub/' . $sub->getName() . '">' . $sub->getName() . '</a>';
                    $tempSub = true;
                }
            };

            //si on a une sous-catégorie on la sépare des pays avec une ligne
            if($tempSub){
                $content .= '<div class="dropdown-divider"></div>';
            }
            

            $continent = $this->em->getRepository(Continent::class)->findAll();
            foreach ($continent as $con){
                //$content .= '<h6 class="dropdown-header">' . $con->getName() .'</h6>';
                $country = $this->em->getRepository(Country::class)->getActiveCountry($con->getId(), $a->getId());
                foreach($country as $count)
                {
                    if(!$count->getArticles()->isEmpty()){
                       $content .= '<a class="dropdown-item" href="/articles/list/' . $a->getName() . '/pays/' . $count->getName() . '">' . $count->getName() . '</a>'; 
                    }
                }
            }

            $content .= '</div></li>';
        }
        $content .= '<li class="nav-item" role="presentation"><a class="nav-link {% if app.request.get(\'_route\') == \'gallery\' %}active{% endif %}" href="{{ path(\'gallery\') }}"><i class="fa fa-image"></i>&nbsp; Photos</a></li>
        <li class="nav-item" role="presentation"><a class="nav-link {% if app.request.get(\'_route\') == \'contact\' %}active{% endif %}" href="{{ path(\'contact\') }}"><i class="fa fa-comment"></i>&nbsp; Contact</a></li></ul></div></div></nav></div>';

        return $content;
    }
}