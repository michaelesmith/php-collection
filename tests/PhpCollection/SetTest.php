<?php

namespace PhpCollection\Tests;

use PhpCollection\Set;
use PhpCollection\SetUtils;

class SetTest extends \PHPUnit_Framework_TestCase
{
    private $set;

    public function testSet()
    {
        $this->assertEquals(3, $this->set->count());
        $this->assertTrue($this->set->contains('foo'));
        $this->assertFalse($this->set->contains('foo2'));

        $this->set->add('foo2');
        $this->assertEquals(4, $this->set->count());
        $this->assertTrue($this->set->contains('foo2'));
    }

    public function testSetSetAll()
    {
        $this->assertEquals(3, $this->set->count());
        $this->set->setAll(array('a', 'b', 'a'));
        $this->assertEquals(5, $this->set->count());
        $finalSetArray = iterator_to_array($this->set);
        sort($finalSetArray);
        $this->assertEquals(array('a', 'b', 'bar', 'baz', 'foo'), $finalSetArray);
    }

    public function testRemove()
    {
        $this->assertEquals(3, $this->set->count());
        $this->assertTrue($this->set->contains('foo'));
        $this->set->remove('foo');
        $this->assertEquals(2, $this->set->count());
        $this->assertFalse($this->set->contains('foo'));
    }

    public function testClear()
    {
        $this->assertCount(3, $this->set);
        $this->set->clear();
        $this->assertCount(0, $this->set);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The set does not contain "asdfasdf".
     */
    public function testRemoveWithUnknownIndex()
    {
        $this->set->remove('asdfasdf');
    }

    public function testContains()
    {
        $this->assertTrue($this->set->contains('foo'));
        $this->assertFalse($this->set->contains('asdf'));
    }

    public function testIsEmpty()
    {
        $this->assertFalse($this->set->isEmpty());
        $this->set->clear();
        $this->assertTrue($this->set->isEmpty());
    }

    public function testFilter()
    {
        $set = new Set(array('a', 'b', 'c', 'd', 'e', 'f'));
        $newSet = $set->filter(function($v) { return $v === 'd'; });

        $this->assertNotSame($newSet, $set);
        $this->assertCount(6, $set);
        $this->assertCount(1, $newSet);
        $this->assertEquals(array('d'), iterator_to_array($newSet));
    }

    public function testFilterNot()
    {
        $set = new Set(array('a', 'b', 'c', 'd', 'e', 'f'));
        $newSet = $set->filterNot(function($v) { return $v === 'd'; });

        $this->assertNotSame($newSet, $set);
        $this->assertCount(6, $set);
        $this->assertCount(5, $newSet);
        $finalSetArray = iterator_to_array($newSet);
        sort($finalSetArray);
        $this->assertEquals(array('a', 'b', 'c', 'e', 'f'), $finalSetArray);
    }

    public function testEquals()
    {
        $set1 = new Set(array('a', 'b', 'c'));
        $set2 = new Set(array('b', 'a', 'c'));
        $this->assertTrue($set1->equals($set2));
        $this->assertTrue($set2->equals($set1));
        $set1->add('d');
        $this->assertFalse($set1->equals($set2));
        $this->assertFalse($set2->equals($set1));
    }

    public function testUnionStatic()
    {
        $otherSet = new Set(array('foo', 'boo'));
        $unionSet = SetUtils::union($otherSet, $this->set);

        $finalSetArray = iterator_to_array($unionSet);
        sort($finalSetArray);
        $this->assertEquals(array('bar', 'baz', 'boo', 'foo'), $finalSetArray);
        $this->assertCount(3, $this->set);
        $this->assertCount(2, $otherSet);
        $this->assertCount(4, $unionSet);
    }
    public function testUnionForward()
    {
        $otherSet = new Set(array('foo', 'boo'));
        $this->set->union($otherSet);
        $finalSetArray = iterator_to_array($this->set);
        sort($finalSetArray);
        $this->assertEquals(array('bar', 'baz', 'boo', 'foo'), $finalSetArray);
    }
    public function testUnionBackward()
    {
        $otherSet = new Set(array('foo', 'boo'));
        $this->set->union($otherSet);
        $otherSet->union($this->set);
        $finalSetArray = iterator_to_array($this->set);
        sort($finalSetArray);
        $this->assertEquals(array('bar', 'baz', 'boo', 'foo'), $finalSetArray);
    }

    public function testIntersectStatic()
    {
        $otherSet = new Set(array('foo', 'boo'));
        $intersectSet = SetUtils::intersect($otherSet, $this->set);

        $this->assertTrue($intersectSet->equals(new Set(array('foo'))));
        $this->assertCount(3, $this->set);
        $this->assertCount(2, $otherSet);
        $this->assertCount(1, $intersectSet);
    }
    public function testIntersectForward()
    {
        $otherSet = new Set(array('foo', 'boo'));
        $this->set->intersect($otherSet);
        $this->assertTrue($this->set->equals(new Set(array('foo'))));
    }
    public function testIntersectBackward()
    {
        $otherSet = new Set(array('foo', 'boo'));
        $otherSet->intersect($this->set);
        $this->assertTrue($otherSet->equals(new Set(array('foo'))));
    }

    public function testSubtractStatic()
    {
        $otherSet = new Set(array('foo', 'boo'));

        $differenceSet1 = SetUtils::subtract($otherSet, $this->set);
        $this->assertTrue($differenceSet1->equals(new Set(array('boo'))));

        $differenceSet2 = SetUtils::subtract($this->set, $otherSet);
        $this->assertTrue($differenceSet2->equals(new Set(array('bar', 'baz'))));
    }
    public function testSubtractForward()
    {
        $otherSet = new Set(array('foo', 'boo'));
        $this->set->subtract($otherSet);
        $this->assertTrue($this->set->equals(new Set(array('bar', 'baz'))));
    }
    public function testSubtractBackward()
    {
        $otherSet = new Set(array('foo', 'boo'));
        $otherSet->subtract($this->set);
        $this->assertTrue($otherSet->equals(new Set(array('boo'))));
    }

    protected function setUp()
    {
        $this->set = new Set();
        $this->set->setAll(array(
            'foo',
            'bar',
            'baz',
            'baz',
            'bar',
        ));
    }
}
