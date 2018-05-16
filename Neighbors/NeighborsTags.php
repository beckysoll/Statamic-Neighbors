<?php

namespace Statamic\Addons\Neighbors;

use Statamic\Extend\Tags;

class NeighborsTags extends Tags
{

    /**
     * The {{ neighbors:[foo] }} tag
     *
     * @return array
     */
    public function __call($method, $arguments)
    {
        $neighborhood = array_get($this->context, $this->tag_method);
        $this->context['current_neighborhood'] = $this->tag_method;

        return $neighborhood ? $this->parseLoop($neighborhood) : null;
        
    }

    /**
     * The {{ neighbors:next }} tag
     *
     * @return string
     */
    public function next()
    {
        return $this->adjacentSet('next', null);
    }

    /**
     * The {{ neighbors:previous }} tag
     *
     * @return string
     */
    public function previous()
    {
        return $this->adjacentSet('previous', null);
    }


    private function adjacentSet($direction,$condition)
    {
        $thisIndex = array_get($this->context, 'zero_index');;
        $currentNeighborhood = $this->context[$this->context['current_neighborhood']];
        $adjacentSibling = null;
        $siblingValue = null;
        $field = $this->getParam('field');

        if ($direction === 'next' && ($thisIndex+1) < array_get($this->context, 'total_results')) {
            $adjacentSibling = array_slice($currentNeighborhood,$thisIndex+1,1)[0];
        }
        elseif ($direction === 'previous' && $thisIndex !== 0) {
            $adjacentSibling = array_slice($currentNeighborhood,$thisIndex-1,1)[0];
        }

        if ($field && isset($adjacentSibling[$field])) {  
            $siblingValue = $adjacentSibling[$field];
        } 
        elseif (!($field)) {
            $siblingValue = $adjacentSibling['type'];
        }

        return $siblingValue;
    }
}