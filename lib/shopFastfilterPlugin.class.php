<?php

class shopFastfilterPlugin extends shopPlugin
{
    public static function getInstance($from = null)
    {
        return wa('shop')->getPlugin('fastfilter');
    }

    /**
     * Получить характеристики и их значения для указанной категории.
     *
     * @param int $category_id
     * @return array
     */
    public function getCategoryFeatures($category_id)
    {
        // Создаем экземпляр модели
        $model = new shopFastfilterSettingsModel();

        // Получаем данные для указанной категории
        $settings = $model->getSettingsByCategory($category_id);

        $return = [];

        // Декодируем JSON значения
        foreach ($settings as &$setting) {
            if (!empty($setting['default_values'])) {
                $setting['default_values'] = json_decode($setting['default_values'], true);
            }
            
            $return[$setting['feature_id']] = [
                'id' => $setting['feature_id'],
                'parent_id' => null,
                'code' => $setting['feature_code'],
                'status' => 'public',
                'name' => $setting['feature_name'],
                'type' => 'varchar',
                'selectable' => 1,
                'multiple' => 1,
                'count' => count($setting['default_values']),
                'available_for_sku' => 0,
                'default_unit' => '',
                'builtin' => 0,
                'values' => array_column($setting['default_values'], 'name', 'id'),
                'isFastFilter' => true
            ];
        }

        return $return;
    }

}
