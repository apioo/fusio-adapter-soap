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

namespace Fusio\Adapter\Soap\Tests\Action;

use Fusio\Adapter\Soap\Connection\Soap;
use Fusio\Adapter\Soap\Tests\SoapTestCase;
use Fusio\Engine\Form\Builder;
use Fusio\Engine\Form\Container;
use Fusio\Engine\Form\Element\Input;
use Fusio\Engine\Form\Element\Select;
use Fusio\Engine\Parameters;

/**
 * SoapTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    https://www.fusio-project.org/
 */
class SoapTest extends SoapTestCase
{
    public function testGetConnection()
    {
        /** @var Soap $connection */
        $connection = $this->getConnectionFactory()->factory(Soap::class);

        $config = new Parameters([
            'location' => 'http://localhost/soap.php',
            'uri'      => 'http://test-uri/',
        ]);

        $soap = $connection->getConnection($config);

        $this->assertInstanceOf(\SoapClient::class, $soap);
    }

    public function testConfigure()
    {
        $connection = $this->getConnectionFactory()->factory(Soap::class);
        $builder    = new Builder();
        $factory    = $this->getFormElementFactory();

        $connection->configure($builder, $factory);

        $this->assertInstanceOf(Container::class, $builder->getForm());

        $elements = $builder->getForm()->getElements();
        $this->assertEquals(6, count($elements));
        $this->assertInstanceOf(Input::class, $elements[0]);
        $this->assertInstanceOf(Input::class, $elements[1]);
        $this->assertInstanceOf(Input::class, $elements[2]);
        $this->assertInstanceOf(Select::class, $elements[3]);
        $this->assertInstanceOf(Input::class, $elements[4]);
        $this->assertInstanceOf(Input::class, $elements[5]);
    }
}
