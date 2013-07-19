<?php

/*
 * Copyright 2013 Steve Hulet <stevehulet@gmail.com>
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

namespace PhpCollection;

use PhpOption\Some;
use PhpOption\None;

/**
 * A simple set implementation which basically wraps an array with an object oriented interface.
 *
 * @IgnoreAnnotation("template")
 * @template {V}
 *
 */
class AbstractSet extends AbstractCollection implements \IteratorAggregate, SetInterface
{
    protected $elements;

    public function __construct(array $elements = array())
    {
        $this->elements = array_unique($elements);
    }

    public function add($value)
    {
        if (!in_array($value, $this->elements)) {
            $this->elements[] = $value;
        }
    }

    /**
     * Adds all key/value pairs in the map.
     *
     * @param array<T> $newElements
     *
     * @return void
     */
    public function setAll(array $newElements)
    {
        foreach ($newElements as $newElement) {
            $this->add($newElement);
        }
    }

    public function remove($value)
    {
        if ( ! in_array($value, $this->elements)) {
            throw new \InvalidArgumentException(sprintf('The set does not contain "%s".', $value));
        }

        $key = array_search($value, $this->elements);
        unset($this->elements[$key]);

        return null;
    }

    public function clear()
    {
        $this->elements = array();
    }

    public function contains($elem)
    {
        return in_array($elem, $this->elements);
    }

    public function isEmpty()
    {
        return empty($this->elements);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    protected function createNew(array $elements)
    {
        return new static($elements);
    }

    public function count()
    {
        return count($this->elements);
    }


    /**
     * Returns a new filtered map.
     *
     * @param callable $callable receives the element and must return true (= keep), or false (= remove).
     *
     * @return AbstractMap
     */
    public function filter($callable)
    {
        return $this->filterInternal($callable, true);
    }

    /**
     * Returns a new filtered map.
     *
     * @param callable $callable receives the element and must return true (= remove), or false (= keep).
     *
     * @return AbstractMap
     */
    public function filterNot($callable)
    {
        return $this->filterInternal($callable, false);
    }

    private function filterInternal($callable, $booleanKeep)
    {
        $newElements = array();
        foreach ($this->elements as $element) {
            if ($booleanKeep !== call_user_func($callable, $element)) {
                continue;
            }

            $newElements[] = $element;
        }

        return $this->createNew($newElements);
    }

    public function foldLeft($initialValue, $callable)
    {
        throw new Exception('Not Implemented');
    }

    public function foldRight($initialValue, $callable)
    {
        throw new Exception('Not Implemented');
    }


    public function equals($otherSet) {
        sort($this->elements);
        $otherSetArray = iterator_to_array($otherSet);
        sort($otherSetArray);
        return $this->elements === $otherSetArray;
    }

    public function union($otherSet) {
        foreach ($otherSet as $element) {
            $this->add($element);
        }
    }

    public function intersect($otherSet) {
        $elementsToRemove = array();
        foreach ($this->elements as $element) {
            if (!$otherSet->contains($element)) {
                $elementsToRemove[] = $element;
            }
        }
        foreach ($elementsToRemove as $element) {
            $this->remove($element);
        }
    }

    public function subtract($otherSet) {
        foreach ($otherSet as $element) {
            if (in_array($element, $this->elements)) {
                $this->remove($element);
            }
        }
    }
}
