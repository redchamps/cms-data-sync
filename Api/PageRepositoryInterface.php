<?php
namespace RedChamps\CmsDataSync\Api;

/**
 * CMS block CRUD interface.
 * @api
 * @since 100.0.2
 */
interface PageRepositoryInterface
{
    /**
     * Retrieve block.
     *
     * @param string $identifier
     * @return \Magento\Cms\Api\Data\PageInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByIdentifier($identifier);
}
