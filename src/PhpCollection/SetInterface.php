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

use PhpOption\Option;

/**
 * Basic set interface.
 *
 * @IgnoreAnnotation("template")
 * @template V the type of the values
 *
 */
interface SetInterface extends CollectionInterface
{


    /**
     * Removes an element from the set.
     *
     * @param V $value
     *
     * @return null
     */
    public function remove($key);

    /**
     * Determines if the given set is equal to the current set.
     *
     * @param MapInterface<V> $otherSet
     *
     * @return bool
     */
    public function equals($otherSet);

    /**
     * Adds all elements from the input set to the current set.
     *
     * @param MapInterface<V> $otherSet
     *
     */
    public function union($otherSet);

    /**
     * Removes from the current set all elements not present in both the 
     * current current set and the input set.
     *
     * @param MapInterface<V> $otherSet
     *
     * @return MapInterface<V>
     */
    public function intersect($otherSet);

    /**
     * Removes all elements from the input set from the current set.
     *
     * @param MapInterface<V> $otherSet
     *
     * @return MapInterface<V>
     */
    public function subtract($otherSet);

}
