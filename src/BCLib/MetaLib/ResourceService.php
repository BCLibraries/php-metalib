<?php

namespace BCLib\MetaLib;

class ResourceService
{
    /**
     * @var Client
     */
    private $_client;

    private $_institute;

    private $_requester_ip;

    public function __construct(Client $client, $requester_ip = null, $institute = null)
    {
        $this->_client = $client;
        $this->_institute = $institute;
        $this->_requester_ip = $requester_ip;
    }

    public function retrieveCategories()
    {
        $op = 'retrieve_categories_request';
        $params = [];
        $params = $this->_loadDefaultParams($params);
        $result = $this->_client->send($op, $params);
        return $this->_loadCategoryList($result);
    }

    public function retrieveQuickSets()
    {
        $op = 'retrieve_quick_sets_request';
        $params = $this->_loadDefaultParams([]);
        $result = $this->_client->send($op, $params);
        return $this->_loadQuickSetList($result);
    }

    public function retrieveAll()
    {
        $categories = $this->retrieveCategories();
        $resources = array();
        foreach ($categories as $category) {
            foreach ($category->subcategories as $subcategory) {
                try {
                    $resources = array_merge($resources, $this->retrieveByCategory($subcategory->sequence));
                } catch (MetaLibException $e) {
                    //echo $e->getMessage();
                }
            }
        }
        $resources = array_unique($resources);
        return $this->_sortResources($resources);
    }

    public function retrieveByCategory($category_id)
    {
        return $this->_retrieveResourceList('retrieve_resources_by_category_request', 'category_id', $category_id);
    }

    public function retrieveByQuickSet($set_id)
    {
        return $this->_retrieveResourceList('retrieve_resources_by_quick_set_request', 'quick_sets_id', $set_id);
    }

    protected function _retrieveResourceList($op, $param_name, $param_value)
    {
        $params = [
            $param_name => $param_value
        ];
        $params = $this->_loadDefaultParams($params);
        $result = $this->_client->send($op, $params);
        return $this->_loadResourceList($result);
    }

    protected function _loadDefaultParams(array $params)
    {
        $default_params = ['institute', 'requester_ip'];
        foreach ($default_params as $param) {
            $param_string = "_$param";
            if (isset($this->$param_string)) {
                $params[$param] = $this->$param_string;
            }
        }
        return $params;
    }

    /**
     * @param \SimpleXMLElement $response_xml
     *
     * @return \BCLib\MetaLib\Resource[]
     */
    protected function _loadResourceList(\SimpleXMLElement $response_xml)
    {
        $list_xml = $response_xml->children()[0];

        if ($list_xml->getName() == 'retrieve_resources_by_quick_set_response') {
            $list_xml = $list_xml->set_info;
        }

        $resource_list = [];

        foreach ($list_xml->source_info as $xml) {
            $resource = new Resource();
            $resource->internal_number = (string) $xml->source_internal_number;
            $resource->number = (string) $xml->source_001;
            $resource->name = (string) $xml->source_name;
            $resource->short_name = (string) $xml->source_short_name;
            $resource->searchable = ($xml->source_searchable_flag == 'Y');
            $resource_list[] = $resource;
        }
        return $resource_list;
    }

    /**
     * @param \SimpleXMLElement $response_xml
     *
     * @return Category[]
     */
    protected function _loadCategoryList(\SimpleXMLElement $response_xml)
    {
        $category_list = [];
        $list_xml = $response_xml->retrieve_categories_response;
        foreach ($list_xml->category_info as $xml) {
            $category = new Category();
            $category->name = (string) $xml->category_name;
            foreach ($xml->subcategory_info as $subcategory_xml) {
                $category->subcategories[] = $this->_loadSubCategories($subcategory_xml);
            }
            $category_list[] = $category;
        }
        return $category_list;
    }

    protected function _loadSubCategories(\SimpleXMLElement $subcategory_xml)
    {
        $subcategory = new Subcategory();
        $subcategory->name = (string) $subcategory_xml->subcategory_name;
        $subcategory->sequence = (string) $subcategory_xml->sequence;
        $subcategory->bases = (string) $subcategory_xml->no_bases;
        return $subcategory;
    }

    /**
     * @param \SimpleXMLElement $response_xml
     *
     * @return QuickSet[]
     */
    protected function _loadQuickSetList(\SimpleXMLElement $response_xml)
    {
        $quickset_list = [];
        $list_xml = $response_xml->retrieve_quick_sets_response;
        foreach ($list_xml->set_info as $set_xml) {
            $quickset_list[] = $this->_loadQuickSet($set_xml);
        }
        return $quickset_list;
    }

    protected function _loadQuickSet(\SimpleXMLElement $set_xml)
    {
        $set = new QuickSet();
        $set->name = (string) $set_xml->set_name;
        $set->sequence = (string) $set_xml->set_sequence;
        $set->bases = (string) $set_xml->no_bases;
        $set->description = (string) $set_xml->set_description;
        return $set;
    }

    /**
     * @param array $resources
     *
     * @return \BCLib\MetaLib\Resource[]
     */
    protected function _sortResources(array $resources)
    {
        usort(
            $resources,
            function ($a, $b) {
                if ($a->name == $b->name) {
                    return 0;
                }

                if (strtolower($a->name) < strtolower($b->name)) {
                    return -1;
                }
                return 1;
            }
        );
        return $resources;
    }
}