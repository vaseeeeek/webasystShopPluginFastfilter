<?php

class shopFastfilterSettingsModel extends waModel
{
    protected $table = 'shop_fastfilter_settings';

    /**
     * Получить настройки для указанной категории и характеристики.
     *
     * @param int $category_id
     * @param int $feature_id
     * @return array
     */
    public function getSettings($category_id, $feature_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE category_id = :category_id AND feature_id = :feature_id";
        return $this->query($sql, ['category_id' => $category_id, 'feature_id' => $feature_id])->fetchAssoc();
    }

    /**
     * Сохранить настройки для указанной категории и характеристики.
     *
     * @param int $category_id
     * @param int $feature_id
     * @param array $default_values
     * @return bool
     */
    public function saveSettings($category_id, $feature_id, $feature_code, $feature_name, $default_values)
    {
        $data = [
            'category_id' => $category_id,
            'feature_id' => $feature_id,
            'feature_code' => $feature_code,
            'feature_name' => $feature_name,
            'default_values' => json_encode($default_values)
        ];
        
        // Проверяем, существует ли запись
        $existing = $this->getSettings($category_id, $feature_id);
        
        if ($existing) {
            // Обновляем запись
            return $this->updateById($existing['id'], $data);
        } else {
            // Вставляем новую запись
            return $this->insert($data);
        }
    }

    /**
     * Удалить настройки по идентификатору.
     *
     * @param int $id
     * @return bool
     */
    public function deleteSettings($id)
    {
        return $this->deleteById($id);
    }

    /**
     * Получить все настройки.
     *
     * @return array
     */
    public function getAllSettings()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->query($sql)->fetchAll();
    }

    public function getFeatValues($featId)
    {
        $sql = "SELECT * FROM shop_feature_values_varchar WHERE feature_id = :featId";
        return $this->query($sql, array('featId' => $featId))->fetchAll();
    }
    
    /**
     * Получить настройки для указанной категории.
     *
     * @param int $category_id
     * @return array
     */
    public function getSettingsByCategory($category_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE category_id = :category_id";
        return $this->query($sql, ['category_id' => $category_id])->fetchAll();
    }
}
