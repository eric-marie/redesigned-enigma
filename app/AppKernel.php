<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            // Bundles vendor supplémentaires
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Dunglas\AngularCsrfBundle\DunglasAngularCsrfBundle(),

            // Bundles du projet
            new AppBundle\AppBundle(),
            new ApiBundle\ApiBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

//    public function getCacheDir()
//    {
//        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
//    }
//
//    public function getLogDir()
//    {
//        return dirname(__DIR__).'/var/logs';
//    }

    /**
     * Optimisation pour Vagrant : on sort le cache du répertoire partagé avec Windows
     *
     * @return string
     */
    public function getCacheDir()
    {
        if (in_array($this->environment, array('dev', 'test', 'prod'))) {
            return '/var/tmp/projetX/cache/' .  $this->environment;
        }

        return parent::getCacheDir();
    }

    /**
     * Optimisation pour Vagrant : on sort les log du répertoire partagé avec Windows
     *
     * @return string
     */
    public function getLogDir()
    {
        if (in_array($this->environment, array('dev', 'test', 'prod'))) {
            return '/var/log/projetX';
        }

        return parent::getLogDir();
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
