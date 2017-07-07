<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    private $developmentEnvironments = ['dev', 'test'];

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
            new Elewant\FrontendBundle\ElewantFrontendBundle(),
        ];

        if (in_array($this->getEnvironment(), $this->developmentEnvironments, true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }

    /**
     * Override the cache dir for Vagrant
     */
    public function getCacheDir()
    {
        if (in_array($this->getEnvironment(), $this->developmentEnvironments)) {
            return '/dev/shm/elewant/cache/' . $this->environment;
        }

        return parent::getCacheDir();
    }

    /**
     * Override the logs dir for Vagrant
     */
    public function getLogDir()
    {
        if (in_array($this->getEnvironment(), $this->developmentEnvironments)) {
            return '/dev/shm/elewant/log/' . $this->environment;
        }

        return parent::getLogDir();
    }

    public function getRootDir()
    {
        return __DIR__;
    }
}
