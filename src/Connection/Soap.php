<?php
/*
 * Fusio - Self-Hosted API Management for Builders.
 * For the current version and information visit <https://www.fusio-project.org/>
 *
 * Copyright (c) Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Fusio\Adapter\Soap\Connection;

use Fusio\Engine\ConnectionAbstract;
use Fusio\Engine\Form\BuilderInterface;
use Fusio\Engine\Form\ElementFactoryInterface;
use Fusio\Engine\ParametersInterface;

/**
 * Soap
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://www.fusio-project.org/
 */
class Soap extends ConnectionAbstract
{
    public function getName(): string
    {
        return 'SOAP';
    }

    public function getConnection(ParametersInterface $config): \SoapClient
    {
        $options = [];

        $version = $config->get('version');
        if (!empty($version)) {
            $options['soap_version'] = $version;
        }

        $login    = $config->get('username');
        $password = $config->get('password');
        if (!empty($login) && !empty($password)) {
            $options['login']    = $login;
            $options['password'] = $password;
        }

        $options['exceptions'] = true;

        $wsdl = $config->get('wsdl');
        if (empty($wsdl)) {
            $wsdl = null;
            $options['location'] = $config->get('location');
            $options['uri']      = $config->get('uri');
        }

        return new \SoapClient($wsdl, $options);
    }

    public function configure(BuilderInterface $builder, ElementFactoryInterface $elementFactory): void
    {
        $builder->add($elementFactory->newInput('wsdl', 'WSDL', 'text', 'Location of the WSDL specification'));
        $builder->add($elementFactory->newInput('location', 'Location', 'text', 'Required if no WSDL is available'));
        $builder->add($elementFactory->newInput('uri', 'Uri', 'text', 'Required if no WSDL is available'));
        $builder->add($elementFactory->newSelect('version', 'Version', [SOAP_1_1 => '1.1', SOAP_1_2 => '1.2'], 'Optional SOAP version'));
        $builder->add($elementFactory->newInput('username', 'Username', 'text', 'Optional username for authentication'));
        $builder->add($elementFactory->newInput('password', 'Password', 'text', 'Optional password for authentication'));
    }
}
