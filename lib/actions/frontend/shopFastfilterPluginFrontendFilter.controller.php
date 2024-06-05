<?php
class shopFastfilterPluginFrontendFilterController extends waJsonController
{
    public function execute()
    {
        $text = waRequest::get('text', '', 'string');
        $category_id = waRequest::get('category_id', 0, 'int');
        $features_id = waRequest::get('features_id', 0, 'int');
        
        // Формируем имя таблицы
        $table_name = 'shop_fastfilter_' . intval($category_id) . '_' . intval($features_id);

        // Проверка существования таблицы
        $model = new waModel();
        try {
            $model->query("SELECT 1 FROM `{$table_name}` LIMIT 1");
        } catch (waDbException $e) {
            $this->setError('Table does not exist');
            return;
        }

        // Запрос данных из таблицы
        $sql = "SELECT feature_value_id, value FROM `{$table_name}` WHERE `value` LIKE ?";
        $results = $model->query($sql, '%' . $text . '%')->fetchAll();

        // Возвращаем JSON ответ
        $this->response = $results;
    }
}
