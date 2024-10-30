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

use AcademicPuma\RestClient\Model\ModelObject;
use DOMAttr;
use DOMNode;

/**
 * Description of ModelDeserializer
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
abstract class ModelUnserializer
{
    protected abstract function unserializeAttributes(DOMNode $node);

    protected abstract function traverseDOM(DOMNode $node);

    protected abstract function setPrimitiveTypeAttribute(ModelObject $object, DOMAttr $attr);

    protected abstract function setComplexTypeAttribute(ModelObject $object, ModelObject $attr);

    public abstract function convertToModel();
}
