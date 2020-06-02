<?php
namespace RedChamps\CmsDataSync\Api;

interface ProcessorInterface
{
    /**
     * Process entity.
     *
     * @param int $id
     * @return array
     */
    public function process($id);
}
