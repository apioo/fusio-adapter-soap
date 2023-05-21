<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2023 Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
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
 * @license http://www.gnu.org/licenses/agpl-3.0
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
