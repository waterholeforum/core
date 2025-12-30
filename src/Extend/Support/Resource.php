<?php

namespace Waterhole\Extend\Support;

/**
 * JSON:API resource extender container.
 *
 * Holds fields, endpoints, sorts, filters, and scope callbacks for a resource.
 */
class Resource
{
    public UnorderedList $scope;
    public UnorderedList $endpoints;
    public UnorderedList $fields;
    public UnorderedList $sorts;
    public UnorderedList $filters;

    public function __construct()
    {
        $this->scope = new UnorderedList();
        $this->endpoints = new UnorderedList();
        $this->fields = new UnorderedList();
        $this->sorts = new UnorderedList();
        $this->filters = new UnorderedList();
    }
}
