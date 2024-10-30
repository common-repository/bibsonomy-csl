<?php
/*
 *
 * restclient-php is a full-featured REST client  written in PHP
 * for PUMA and/or BibSonomy.
 * www.bibsonomy.org
 * www.academic-puma.de
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation, either version 3
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */


namespace AcademicPuma\RestClient\Util\Collection;


use PHPUnit\Framework\TestCase;

class ArrayListTest extends TestCase
{


    /**
     * @var ArrayList
     */
    private $arrayList1;

    /**
     * @var ArrayList
     */
    private $arrayList2;

    private $data = ['a', 'b', 'c', 'd'];

    private $dataAssoc = ['a' => 0, 'b' => 1, 'c' => 2, 'd' => 3];

    public function setUp(): void
    {
        $this->arrayList1 = new ArrayList($this->data);
        $this->arrayList2 = new ArrayList($this->dataAssoc);
    }

    public function testLoop()
    {
        $i = 0;
        foreach ($this->arrayList2 as $key => $elem) {
            $this->assertTrue($elem === $i);
            $this->assertTrue($key === $this->arrayList1->get($i));
            ++$i;
        }
    }

    public function testArrayAccess()
    {
        $this->assertArrayHasKey('a', $this->arrayList2);
        $this->assertNotEmpty($this->arrayList1[0]);
        $this->assertTrue($this->arrayList1[0] === 'a');
    }

    public function testToArray()
    {
        $this->assertNotTrue(is_array($this->arrayList1));
        $this->assertTrue(is_array($this->arrayList1->toArray()));
    }

    public function testCount()
    {
        $this->assertTrue($this->arrayList1->count() === 4);
        $this->assertTrue($this->arrayList2->count() === 4);
    }

    public function testClear()
    {
        $this->assertEmpty($this->arrayList1->clear());
    }

    public function testAdd()
    {
        $this->arrayList1->clear();
        $a = ['a', 'b', 'c'];
        $this->arrayList1->add(0, $a);
        $this->assertEquals('a', $this->arrayList1[0][0]);
        $this->assertEquals('b', $this->arrayList1[0][1]);
        $this->assertEquals('c', $this->arrayList1[0][2]);
        $this->arrayList1->add(0, 'd');
        $this->assertEquals('d', $this->arrayList1[0][3]);
    }

    public function testSet()
    {

        $this->arrayList1->set(0, 'a');
        $this->assertEquals('a', $this->arrayList1[0]);
    }

    public function testRemove()
    {
        $this->arrayList1->set(0, 'a');
        $this->arrayList1->remove(0);
        $this->assertNull($this->arrayList1[0]);
    }

    public function testHasValue()
    {
        $this->arrayList1->set(0, 'a');
        $this->assertTrue($this->arrayList1->hasValue('a') === 0); //index
        $this->arrayList1->remove(0);
        $this->assertFalse($this->arrayList1->hasValue('a')); //false: not found
    }

    public function testHasKey()
    {
        $this->arrayList1->clear();
        $this->arrayList1->add('foo', 'bar');
        $this->assertTrue($this->arrayList1->hasKey('foo'));
        $this->assertFalse($this->arrayList1->hasKey('bar'));
    }

    public function testReplace()
    {
        $this->arrayList1 = new ArrayList(['a', 'b', 'c']);
        $this->arrayList1->replace(['d', 'e', 'f']);
        $this->assertFalse($this->arrayList1->hasValue('a'));
        $this->assertFalse($this->arrayList1->hasValue('b'));
        $this->assertFalse($this->arrayList1->hasValue('c'));
        $this->assertTrue($this->arrayList1->hasValue('d') === 0);
        $this->assertTrue($this->arrayList1->hasValue('e') === 1);
        $this->assertTrue($this->arrayList1->hasValue('f') === 2);
    }

}
