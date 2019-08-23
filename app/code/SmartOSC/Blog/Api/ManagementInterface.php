<?php


namespace SmartOSC\Blog\Api;


/**
 * Interface ManagementInterface
 * @package SmartOSC\Blog\Api
 */
interface ManagementInterface
{

    /**
     * @param $data
     * @return mixed
     */
    public function create($data);

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * @param $id
     * @return mixed
     */
    public function get($id);

    /**
     * @param $id
     * @param $storeId
     * @return mixed
     */
    public function view($id, $storeId);

    /**
     * @param $type
     * @param $term
     * @param $storeId
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getList($type, $term, $storeId, $page, $limit);

}