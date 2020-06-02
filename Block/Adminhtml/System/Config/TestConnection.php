<?php
namespace RedChamps\CmsDataSync\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;

class TestConnection extends Field
{
    /**
     * Set template to itself
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('RedChamps_CmsDataSync::system/config/test_connection.phtml');
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function _getFieldMapping()
    {
        return [
            'url' => 'cms_data_sync_connection_site_url',
            'consumer_key' => 'cms_data_sync_connection_consumer_key',
            'consumer_secret' => 'cms_data_sync_connection_consumer_secret',
            'access_token' => 'cms_data_sync_connection_access_token',
            'access_token_secret' => 'cms_data_sync_connection_access_token_secret'
        ];
    }

    /**
     * Unset some non-related element parameters
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Get the button and scripts contents
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $originalData = $element->getOriginalData();
        $this->addData(
            [
                'button_label' => __($originalData['button_label']),
                'html_id' => $element->getHtmlId(),
                'ajax_url' => $this->_urlBuilder->getUrl('cms_data_sync/action/testConnection'),
                'field_mapping' => str_replace('"', '\\"', json_encode($this->_getFieldMapping()))
            ]
        );

        return $this->_toHtml();
    }
}
