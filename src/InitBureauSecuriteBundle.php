<?php

namespace Webgiciel2\InitBureauSecurite;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Webgiciel2\InitBureauSecurite\DependencyInjection\InitBureauSecuriteExtension;

class InitBureauSecuriteBundle extends Bundle
{
    public function getContainerExtension(): ?InitBureauSecuriteExtension
    {
        return new InitBureauSecuriteExtension();
    }
}
