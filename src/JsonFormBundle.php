<?php

/**
 * Symfony bundle to transform JSON structures into dynamic forms
 *
 * @author Christophe Abillama <christophe.abillama@gmail.com>
 * @license Apache-2.0
 */

namespace Ambelz\JsonToFormBundle;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Ambelz\JsonToFormBundle\DependencyInjection\JsonFormExtension;

/**
 * Main bundle for JSON to Symfony forms transformation
 */
class JsonFormBundle extends AbstractBundle
{
    public function getPath(): string
    {
        return dirname(__DIR__);
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new JsonFormExtension();
    }
}
