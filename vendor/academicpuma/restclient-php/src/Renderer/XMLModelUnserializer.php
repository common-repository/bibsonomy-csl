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

namespace AcademicPuma\RestClient\Renderer;

use AcademicPuma\RestClient\Config\Entrytype;
use AcademicPuma\RestClient\Config\ModelUtils;
use AcademicPuma\RestClient\Config\Resourcetype;
use AcademicPuma\RestClient\Model\Bibtex;
use AcademicPuma\RestClient\Model\Exceptions\InvalidModelObjectException;
use AcademicPuma\RestClient\Model\Group;
use AcademicPuma\RestClient\Model\ModelObject;
use AcademicPuma\RestClient\Util;
use AcademicPuma\RestClient\Util\Tex\TexDecode;
use DOMAttr;
use DOMDocument;
use DOMException;
use DOMNode;
use DOMText;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

/**
 * Converts XML representation of the model into model objects.
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
class XMLModelUnserializer extends ModelUnserializer
{

    protected $validModelTypes = [
        'bibtex',
        'bookmark',
        'group',
        'groups',
        'post',
        'posts',
        'resource',
        'tag',
        'tags',
        'subTags',
        'user',
        'users',
        'documents',
        'document',
        'person',
        'persons',
        'goldStandardPublication',
        'goldStandardBookmark',
        'resourcePersonRelation',
        'resourcePersonRelations',
        'project',
        'projects'
    ];

    protected $validSemiComplexModelTypes = [
        'mainName',
        'relationType',
        'personIndex'
    ];

    protected $xmlString;
    protected $xmlDom;
    protected $texDecoder;

    private $treatCurlyBraces;
    private $treatBackslashes;
    private $bibTexCleaning;

    /**
     *
     * @param string $xmlString
     * @param int $treatCurlyBraces Determines how to treat curly braces in title, abstract and author field.
     * @param int $treatBackslashes Determines how to treat backslashes in title, abstract and author field.
     * @param bool $bibTexCleaning Determines whether BibTex cleaning will be executed.
     */
    public function __construct(string $xmlString, int $treatCurlyBraces = ModelUtils::CB_KEEP_IN_MATH_MODE, int $treatBackslashes = ModelUtils::BS_KEEP_IN_MATH_MODE, bool $bibTexCleaning = true)
    {
        $this->treatCurlyBraces = $treatCurlyBraces;
        $this->treatBackslashes = $treatBackslashes;
        $this->bibTexCleaning = $bibTexCleaning;
        $this->xmlString = $this->normalizeXmlString($xmlString);
        $this->texDecoder = new TexDecode();
    }

    private function normalizeXmlString($xmlString)
    {
        if (!preg_match('!^(<\?xml version="1.0"\?>)(.+)!', $xmlString)) {
            /*$xmlString = '<?xml version="1.0"?>'.$xmlString;*/
        }

        return $xmlString;
    }

    /**
     * @return ModelObject
     * @throws DOMException
     * @throws ReflectionException
     */
    public function convertToModel()
    {
        $this->xmlDom = new DOMDocument();

        $this->xmlDom->loadXML($this->xmlString, LIBXML_NOBLANKS);

        $rootNode = $this->xmlDom->childNodes->item(0)->childNodes->item(0);

        return $this->traverseDOM($rootNode);
    }

    /**
     * @param DOMNode $node
     * @return ModelObject
     * @throws ReflectionException|DOMException
     */
    protected function traverseDOM(DOMNode $node)
    {
        try {
            $modelObject = $this->createModelObject($node);
        } catch (InvalidModelObjectException $e) {
            return null;
        }

        if ($node->hasChildNodes()) {
            for ($i = 0; $i < $node->childNodes->length; ++$i) {
                $child = $node->childNodes->item($i);

                if ($child instanceof DOMText) {
                    continue;
                }

                if (in_array($child->nodeName, $this->validSemiComplexModelTypes)) {
                    $this->setSemiComplexModelAttribute($modelObject, $child);
                    continue;
                }

                if (!in_array($child->nodeName, $this->validModelTypes)) {
                    continue;
                }

                $childObject = $this->traverseDOM($child);
                $this->setComplexTypeAttribute($modelObject, $childObject);

            }
        }

        if ($node->hasAttributes()) {

            for ($i = 0; $i < $node->attributes->length; ++$i) {
                $attr = $node->attributes->item($i);
                $this->setPrimitiveTypeAttribute($modelObject, $attr);
            }
        }
        return $modelObject;
    }

    /**
     * @param DOMNode $node
     *
     * @return mixed
     */
    private function createModelObject(DOMNode $node)
    {

        $nodeName = ucfirst($node->nodeName);
        $className = '\\AcademicPuma\\RestClient\\Model\\' . $nodeName;

        if (class_exists($className)) {
            if (Util\StringUtils::endsWith($className, '\\Resource')) {
                $className .= 'Link';
            }
            return new $className;
        }

        throw new InvalidArgumentException('Node "' . $node->nodeName . '" does not represent a valid model class.');
    }

    /**
     * Sets <code>$attr</code> on <pre>$object</pre>
     *
     * @param ModelObject $object
     * @param ModelObject $attr
     * @throws ReflectionException
     */
    protected function setComplexTypeAttribute(ModelObject $object, ModelObject $attr)
    {

        //class name of the child equates the attribute name of the father
        $matches = [];
        if (!preg_match('!([^\\\]+)$!', get_class($attr), $matches)) {
            throw new InvalidArgumentException('Invalid ModelObject.');
        }
        $attribute = $matches[0];
        //create reflection class of the father
        $reflClass = new ReflectionClass($object);

        //get method
        if ($object instanceof Util\Collection\ArrayList) {
            $method = $reflClass->getMethod('add');
            $method->invoke($object, $object->count(), $attr);

            return;
        }

        if ($attribute === Resourcetype::BOOKMARK ||
            $attribute === Resourcetype::BIBTEX ||
            $attribute === Resourcetype::GOLD_STANDARD_PUBLICATION ||
            $attribute === Resourcetype::GOLD_STANDARD_BOOKMARK) {
            $method = $reflClass->getMethod('setResource');
        } else if ($attribute === 'Tag') {
            $method = $reflClass->getMethod('addTag');
        } else if ($object instanceof Group && $attribute === 'User') {
            $method = $reflClass->getMethod('addUser');
        } else {
            $method = $reflClass->getMethod('set' . $attribute);
        }
        //invoke method on $object and assign $attr
        $method->invoke($object, $attr);
    }

    /**
     * @param DOMNode $node
     * @throws ReflectionException
     */
    protected function unserializeAttributes(DOMNode $node)
    {
        for ($i = 0; $i < $node->attributes->length; ++$i) {
            $attr = $node->attributes->item($i);
            $this->setPrimitiveTypeAttribute($node, $attr);
        }
    }

    /**
     * @param ModelObject $object
     * @param DOMAttr $attr
     * @throws ReflectionException
     */
    protected function setPrimitiveTypeAttribute(ModelObject $object, DOMAttr $attr)
    {
        //create reflection class of the father
        $reflClass = new ReflectionClass($object);
        try {
            //get method
            $method = $reflClass->getMethod('set' . ucfirst($attr->name));

            $value = $attr->value;

            if ($object instanceof Bibtex) {

                //remove double spaces and replace newline by spaces
                $value = Util\StringUtils::clean($value);

                //replace tex macros
                if ($attr->name === Entrytype::MISC) {
                    $value = $this->texDecoder->decode($value, ModelUtils::CB_KEEP, ModelUtils::BS_KEEP, $this->bibTexCleaning);
                } else {
                    $value = $this->texDecoder->decode($value, $this->treatCurlyBraces, $this->treatBackslashes, $this->bibTexCleaning);
                }
            }

            //invoke method on $object and assign $child
            $method->invoke($object, $value);
        } catch (ReflectionException $e) {
            return;
        }
    }

    /**
     * @param ModelObject $object
     * @param DOMNode $node
     * @return void
     * @throws ReflectionException
     * @throws DOMException
     */
    protected function setSemiComplexModelAttribute(ModelObject $object, DOMNode $node)
    {
        $value = '';
        if ($node->hasAttributes()) {
            for ($i = 0; $i < $node->attributes->length; ++$i) {
                $attr = $node->attributes->item($i);
                $value .= $attr->nodeValue . ' ';
            }
        } else {
            $value .= $node->nodeValue;
        }
        $newAttr = new DOMAttr($node->nodeName, trim($value));
        $this->setPrimitiveTypeAttribute($object, $newAttr);
    }
}
