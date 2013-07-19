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


class SetUtils
{

    /**
     * This class should not be instantiated
     */
    private function __construct() {}
    
    static function union($set1, $set2) {
        $newSet = new Set();
        foreach ($set1 as $element) {
            $newSet->add($element);
        }
        foreach ($set2 as $element) {
            $newSet->add($element);
        }
        return $newSet;
    }

    static function intersect($set1, $set2) {
        $newSet = new Set();
        foreach ($set1 as $element) {
            if ($set2->contains($element)) {
                $newSet->add($element);
            }
        }
        return $newSet;
    }

    static function subtract($set1, $set2) {
        $newSet = new Set();
        foreach ($set1 as $element) {
            if (!$set2->contains($element)) {
                $newSet->add($element);
            }
        }
        return $newSet;
    }
}

