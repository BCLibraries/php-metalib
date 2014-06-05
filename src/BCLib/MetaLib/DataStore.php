<?php

namespace BCLib\MetaLib;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use BCLib\MetaLib\Models\Resource;

class DataStore
{
    /**
     * @var EntityManager
     */
    protected $_em;

    protected $_resource_hash;

    public function __construct($db, $user, $passwd, $host, $is_dev)
    {
        $conn = array(
            'dbname'   => $db,
            'user'     => $user,
            'password' => $passwd,
            'host'     => $host,
            'driver'   => 'pdo_mysql',
        );

        $config = Setup::createYAMLMetadataConfiguration([__DIR__ . "/../../../config/yaml"], $is_dev);
        $this->_em = EntityManager::create($conn, $config);
    }

    public function persist($category)
    {
        $this->_em->persist($category);
    }

    public function merge($to_merge)
    {
        $this->_em->merge($to_merge);
    }

    public function flush()
    {
        $this->_em->flush();
    }

    /**
     * @return Resource
     */
    public function getResource(Resource $res)
    {
        if (!isset($this->_resource_hash[$res->number])) {
            $this->_resource_hash[$res->number] = $res;
        }
        return $this->_resource_hash[$res->number];
    }
}