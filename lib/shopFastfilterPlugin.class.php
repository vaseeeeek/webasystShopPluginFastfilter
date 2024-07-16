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
    public static function getCategoryFeatures($category_id)
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
    
    public function frontendCategory($category)
    {
        // Получаем текущие параметры фильтра из URL
        $filter_params = waRequest::get();
        
        // Проверяем наличие параметров фильтра
        if (isset($filter_params['razrabotchik'])) {
            $feature_value_id = $filter_params['razrabotchik'][0];  // Предполагаем, что это razrabotchik[]

            // Формируем имя таблицы
            $category_id = $category['id'];
            $features_id = 6;  // Значение для вашего примера
            $table_name = 'shop_fastfilter_' . intval($category_id) . '_' . intval($features_id);
            $model = new waModel();

            try {
                $seo_url = $model->query("SELECT seo_url FROM `{$table_name}` WHERE `feature_value_id` = ?", $feature_value_id)->fetchField();
                if ($seo_url) {
                    // Выполняем редирект на SEO-friendly URL
                    wa()->getResponse()->redirect(wa()->getRouteUrl('/games/' . $seo_url));
                    return;
                }
            } catch (waDbException $e) {
                waLog::log($e->getMessage(), 'fastfilter.log');
            }
        }
    }

}
