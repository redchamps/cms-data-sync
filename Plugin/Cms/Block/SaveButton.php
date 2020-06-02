<?php
namespace RedChamps\CmsDataSync\Plugin\Cms\Block;

class SaveButton
{
    public function afterGetButtonData(\Magento\Cms\Block\Adminhtml\Block\Edit\SaveButton $subject, $result)
    {
        $result['options'][] = [
            'label' => __('Save & Sync'),
            'id_hard' => 'save_and_sync',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'cms_block_form.cms_block_form',
                                'actionName' => 'save',
                                'params' => [
                                    true,
                                    [
                                        'back' => 'continue',
                                        'action' => 'sync',
                                        "type" => 'block'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        return $result;
    }
}
