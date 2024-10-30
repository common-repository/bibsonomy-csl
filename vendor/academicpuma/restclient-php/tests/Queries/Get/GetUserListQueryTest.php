<?php
/*
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace AcademicPuma\RestClient\Queries\Get;

use AcademicPuma\RestClient\Model;
use PHPUnit\Framework\TestCase;

/**
 * Short description
 *
 * @since 11.06.15
 * @author Sebastian Böttger / boettger@cs.uni-kassel.de
 */
class GetUserListQueryTest extends TestCase
{

    /**
     * @var GetUserListQuery
     */
    protected $getUserListQuery;

    /**
     * @dataProvider executeProvider
     */
    public function testExecute($accessor, $start, $end)
    {

        $this->getUserListQuery = new GetUserListQuery($start, $end);

        // Check status code.
        $statusCode = $this->getUserListQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        if ($start === 1) {
            // Check content.
            $body = $this->getUserListQuery->getBody();
            $this->assertContains('<users start="1"', $body);
        } else {
            // Check content.
            $body = $this->getUserListQuery->getBody();
            $this->assertContains('<users start="0"', $body);
        }

        // Check type.
        $users = $this->getUserListQuery->model();
        $this->assertTrue($users instanceof Model\Users);

        // Is executed flag set?
        $this->assertTrue($this->getUserListQuery->isExecuted());
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH, 1, 5)
        );
    }
}
