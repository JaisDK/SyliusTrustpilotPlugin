<?php

/** @noinspection TraitsPropertiesConflictsInspection */

declare(strict_types=1);

namespace Setono\SyliusTrustpilotPlugin;

use Setono\SyliusTrustpilotPlugin\DependencyInjection\Compiler\CompositeOrderEligibilityCheckerPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class SetonoSyliusTrustpilotPlugin extends AbstractResourceBundle
{
    use SyliusPluginTrait;

    /**
     * {@inheritdoc}
     */
    public function getSupportedDrivers(): array
    {
        return [
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new CompositeOrderEligibilityCheckerPass());
    }
}
