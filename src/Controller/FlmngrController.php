<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use EdSDK\FlmngrServer\Flmngr;
use Symfony\Component\HttpKernel\KernelInterface;

class FlmngrController extends AbstractController
{
    public function index()
    {
        $root = __DIR__ . '../../public/flmngr/';
        Flmngr::flmngrRequest(
            array(
                'dirFiles' => $root . 'files',
                'dirTmp'   => $root . 'tmp',
                'dirCache' => $root . 'cache'
            )
        );

        return new Repsonse;
    }
}
