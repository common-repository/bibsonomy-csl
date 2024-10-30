<?php


namespace AcademicPuma\RestClient\Model;


use AcademicPuma\RestClient\Config\Sorting;
use AcademicPuma\RestClient\Util\Collection\ArrayList;
use AcademicPuma\RestClient\Util\Collection\Comparator;

class Projects extends ArrayList implements ModelObject
{
    public function __toString()
    {
        return '[' . implode(',', $this->array) . ']';
    }

    /**
     * Sorts the list of project objects
     * @param string $order sorting order (allowed values: asc|desc)
     *
     * @return $this
     */
    public function sort(string $order = Sorting::ORDER_ASC): Projects
    {
        $comparator = new Comparator($order);
        usort($this->array, [$comparator, "compare"]);
        return $this;
    }

}