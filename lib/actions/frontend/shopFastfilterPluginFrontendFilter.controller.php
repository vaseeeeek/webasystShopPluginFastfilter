<?php

class shopFastfilterPluginFrontendFilterController extends waJsonController
{
    public function execute()
    {
        $text = waRequest::get('text', '', 'string');
        $category_id = waRequest::get('category_id', 0, 'int');
        $features_id = waRequest::get('features_id', 0, 'int');
        $get_all = waRequest::get('get_all', 0, 'int');

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
        if ($get_all) {
            $sql = "SELECT feature_value_id, value FROM `{$table_name}`";
            $results = $model->query($sql)->fetchAll();
        } else {
            $sql = "SELECT feature_value_id, value FROM `{$table_name}` WHERE `value` LIKE ?";
            $results = $model->query($sql, '%' . $text . '%')->fetchAll();
        }

        // Возвращаем JSON ответ
        $this->response = $results;
    }
    
    /*
    public function execute()
    {
        $category_id = waRequest::get('category_id', 0, 'int');
        $features_id = waRequest::get('features_id', 0, 'int');
        $get_all = waRequest::get('get_all', 0, 'int');
        $cache_key = "filters_html_{$category_id}_{$features_id}";

        // Получение кэша
        $cache = wa()->getCache('default');
        $cache->delete($cache_key);
        if ($html = $cache->get($cache_key)) {
            $this->response = ['html' => $html];
            return;
        }

        // Формируем имя таблицы
        $table_name = 'shop_fastfilter_' . intval($category_id) . '_' . intval($features_id);
        $model = new waModel();

        try {
            $model->query("SELECT 1 FROM `{$table_name}` LIMIT 1");
        } catch (waDbException $e) {
            $this->setError('Table does not exist');
            return;
        }

        // Запрос данных из таблицы
        if ($get_all) {
            $sql = "SELECT feature_value_id, value FROM `{$table_name}`";
            $results = $model->query($sql)->fetchAll();
        } else {
            $text = waRequest::get('text', '', 'string');
            $sql = "SELECT feature_value_id, value FROM `{$table_name}` WHERE `value` LIKE ?";
            $results = $model->query($sql, '%' . $text . '%')->fetchAll();
        }

        // Создание HTML разметки
        $html = '';
        // waLog::dump(count($results));
        foreach ($results as $item) {
            $html .= "<label class='filter-el_opts-el label-flex filter-opt_el'>";
            $html .= "<span class='js-style-check style-check fi-rr-check'>";
            $html .= "<input class='js-style-check-input' type='checkbox' name='filter[]' value='{$item['feature_value_id']}' data-code='{$item['feature_value_id']}'>";
            $html .= "</span>";
            $html .= "<span class='fiwex-feat-val' data-code='{$item['feature_value_id']}' data-feat_val_id='{$item['feature_value_id']}' data-fiwex-parent_id='{$features_id}'>{$item['value']}</span>";
            $html .= "</label>";
        }

        // Сохранение в кэш на 24 часа
        $cache->set($cache_key, $html, 86400);

        $this->response = ['html' => $html];
    }
    */
}
