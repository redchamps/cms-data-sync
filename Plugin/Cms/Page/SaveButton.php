<?php

namespace RedChamps\CmsDataSync\Plugin\Cms\Page;

class SaveButton
{
    public function afterGetButtonData(\Magento\Cms\Block\Adminhtml\Page\Edit\SaveButton $subject, $result)
    {
        $result['options'][] = [
            'label' => __('Save & Sync'),
            'id_hard' => 'save_and_sync',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'cms_page_form.cms_page_form',
                                'actionName' => 'save',
                                'params' => [
                                    true,
                                    [
                                        'back' => 'continue',
                                        'action' => 'sync',
                                        'type' => 'page'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
        ];
        return $result;
    }
}
