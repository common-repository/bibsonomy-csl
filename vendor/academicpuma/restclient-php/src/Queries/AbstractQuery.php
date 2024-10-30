<?php

/*
 *  restclient-php is a full-featured REST client for PUMA and/or
 *  BibSonomy.
 *
 *  Copyright (C) 2015
 *
 *  Knowledge & Data Engineering Group,
 *  University of Kassel, Germany
 *  http://www.kde.cs.uni-kassel.de/
 *
 *  HothoData GmbH, Germany
 *  http://www.academic-puma.de
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace AcademicPuma\RestClient\Queries;

use AcademicPuma\RestClient\Config\ModelUtils;
use AcademicPuma\RestClient\Config\RESTConfig;
use AcademicPuma\RestClient\Model;
use AcademicPuma\RestClient\Model\Exceptions\UnsupportedOperationException;
use AcademicPuma\RestClient\Model\ModelObject;
use AcademicPuma\RestClient\Renderer\XMLModelUnserializer;
use AcademicPuma\RestClient\Util;
use AcademicPuma\RestClient\Util\UrlBuilder;
use DOMException;
use Exception;
use GuzzleHttp\Client;
use Psr\Http\Message\StreamInterface;
use ReflectionException;
use SimpleXMLElement;

/**
 * An abstract query from which all other queries inherit.
 * Queries prepare URL's for API calls, send them and hold the response.
 *
 * @author Florian Fassing
 * @author Sebastian Böttger
 */
abstract class AbstractQuery
{
    protected $urlBuilder;
    protected $url;

    protected $executed;
    protected $response;
    protected $body;
    protected $model;
    protected $client;

    public function __construct()
    {
        $this->executed = false;
        $this->urlBuilder = new Util\UrlBuilder();
        $this->client = new Client();
    }

    /**
     * Executes the query and returns a response
     *
     * @param Client $client
     * @param array $reqOpts Request options containing body, headers or curl options.
     * @return self;
     */
    public abstract function execute(Client $client, array $reqOpts): AbstractQuery;


    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }


    // TODO: Test following functions

    /**
     * @return integer
     */
    public function getStatusCode(): int
    {
        return intval($this->response->getStatusCode());
    }

    /**
     * @return bool
     */
    public function isExecuted(): bool
    {
        return $this->executed;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }

    /**
     * @return string
     */
    public function getReasonPhrase(): string
    {
        return $this->response->getReasonPhrase();
    }

    /**
     * @return string
     * @throws UnsupportedOperationException
     */
    public function getBody(): string
    {

        if ($this->executed === false) {
            throw new UnsupportedOperationException("Cannot access response, first execute query.");
        }

        if (empty($this->body)) {
            $this->body = $this->response->getBody()->getContents();
        }

        return $this->body;
    }

    /**
     * @throws UnsupportedOperationException
     * @throws Exception
     */
    protected function getResponseXML(): SimpleXMLElement
    {
        if ($this->executed === false) {
            throw new UnsupportedOperationException("Cannot access response, first execute query.");
        }

        return new SimpleXMLElement((string) $this->response->getBody());
    }

    /**
     * @return StreamInterface
     */
    public function getStream(): StreamInterface
    {
        return $this->response->getBody();
    }

    /**
     * @param int $treatCurlyBraces Determines how to treat curly braces in title, abstract and author field.
     * @param int $treatBackslashes Determines how to treat backslashes in title, abstract and author field.
     * @param bool $bibTexCleaning Determines whether BibTex cleaning will be executed.
     * @return Model\ModelObject
     * @throws UnsupportedOperationException
     * @throws DOMException
     * @throws ReflectionException
     */
    public function model(int $treatCurlyBraces = ModelUtils::CB_KEEP, int $treatBackslashes = ModelUtils::BS_KEEP, bool $bibTexCleaning = true): ModelObject
    {

        if ($this->executed === false) {
            throw new UnsupportedOperationException("Cannot create Model, first execute query.");
        }

        if (empty($this->model)) {
            $this->model = (new XMLModelUnserializer($this->getBody(), $treatCurlyBraces, $treatBackslashes, $bibTexCleaning))
                ->convertToModel();
        }
        return $this->model;
    }


}
