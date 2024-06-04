<?php

class shopFastfilterPlugin extends shopPlugin
{
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

        // Декодируем JSON значения
        foreach ($settings as &$setting) {
            if (!empty($setting['default_values'])) {
                $setting['default_values'] = json_decode($setting['default_values'], true);
            }
        }

        return $settings;
    }

}
