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

namespace AcademicPuma\RestClient\Config;

/**
 * DO NOT CHANGE any constant values after a release
 *
 * @package BibSonomyAPI - PHP REST-client for BibSonomy API and PUMA API.
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
class RESTConfig
{
    const POSTS_URL = "posts";

    const POSTS_ADDED_SUB_PATH = "added";

    const POSTS_ADDED_URL = "posts/added";

    const POSTS_POPULAR_SUB_PATH = "popular";

    const POSTS_POPULAR_URL = "posts/popular";

    const COMMUNITY_SUB_PATH = "community";

    const API_USER_AGENT = "BibSonomyWebServiceClient";

    const SYNC_URL = "sync";

    const CONCEPTS_URL = "concepts";

    const TAGS_URL = "tags";

    const REFERENCES_SUB_PATH = "references";

    const USERS_URL = "users";

    const DOCUMENTS_SUB_PATH = "documents";

    const FRIENDS_SUB_PATH = "friends";

    const FOLLOWERS_SUB_PATH = "followers";

    const GROUPS_URL = "groups";

    const PERSONS_URL = "persons";

    const RELATIONS_URL = "relations";

    const MERGE_URL = "merge";

    const PROJECTS_URL = "projects";

    const ORGANIZATIONS_URL = "organizations";

    const RESOURCE_TYPE_PARAM = "resourcetype";

    const RESOURCE_PARAM = "resource";

    const TAGS_PARAM = "tags";

    const FILTER_PARAM = "filter";

    const ORDER_PARAM = "order";

    const CONCEPT_STATUS_PARAM = "status";

    const SEARCH_PARAM = "search";

    const SUB_TAG_PARAM = "subtag";

    const REGEX_PARAM = self::FILTER_PARAM;

    const SORTKEY_PARAM = "sortkey";

    const SORTORDER_PARAM = "sortorder";

    const START_PARAM = "start";

    const END_PARAM = "end";

    const STARTDATE_PARAM = "startDate";

    const ENDDATE_PARAM = "endDate";

    const SEARCHTYPE_PARAM = "searchtype";

    const SYNC_STRATEGY_PARAM = "strategy";

    const SYNC_DIRECTION_PARAM = "direction";

    const SYNC_DATE_PARAM = "date";

    const SYNC_STATUS = "status";

    const CLIPBOARD_SUBSTRING = "clipboard";

    const CLIPBOARD_CLEAR = "clear";

    const TAG_REL_PARAM = "relation";

    const FORMAT_PARAM = "format";

    /**
     * Request Attribute ?relation="incoming/outgoing"
     */
    const ATTRIBUTE_KEY_RELATION = "relation";

    /** value for "incoming" */
    const INCOMING_ATTRIBUTE_VALUE_RELATION = "incoming";

    /** value for "outgoing" */
    const OUTGOING_ATTRIBUTE_VALUE_RELATION = "outgoing";

    /** default value */
    const DEFAULT_ATTRIBUTE_VALUE_RELATION = self::INCOMING_ATTRIBUTE_VALUE_RELATION;

    /** Co-Occurring tags. */
    const RELATED_TAG_RELATION = 'related';

    /** Cosine-similar tags */
    const SIMILAR_TAG_RELATION = 'similar';

    /** place holder for the login user - used e.g. for OAuth requests */
    const USER_ME = "@me";
}
