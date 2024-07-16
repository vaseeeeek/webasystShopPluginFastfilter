<?php

class shopFastfilterPluginFrontendCheckfilterintersectionController extends waJsonController
{
    public function execute()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['category_id']) && isset($data['selected_filters']) && isset($data['not_selected_filters'])) {
            $category_id = $data['category_id'];
            $selected_filters = $data['selected_filters'];
            $not_selected_filters = $data['not_selected_filters'];

            $result = $this->checkFiltersIntersection($category_id, $selected_filters, $not_selected_filters);

            $this->response = [
                'has_products' => $result,
                'status' => 'success'
            ];
        } else {
            $this->response = [
                'error' => 'Invalid data structure',
                'status' => 'error'
            ];
        }
    }

    private function checkFiltersIntersection($category_id, $selected_filters, $not_selected_filters)
    {
        $model = new waModel();
        $results = [];

        foreach ($not_selected_filters as $filter) {
            $feature_value_id = $filter['value_id'];

            $sql = "
            SELECT 1
            FROM shop_product p
            JOIN shop_product_features pf ON p.id = pf.product_id
            WHERE p.category_id = :category_id
              AND pf.feature_value_id = :feature_value_id
              AND p.status = 1
        ";

            $params = [
                'category_id' => $category_id,
                'feature_value_id' => $feature_value_id,
            ];

            // Создаем подзапросы для каждого feature_id
            $grouped_filters = [];
            foreach ($selected_filters as $selected_filter) {
                $grouped_filters[$selected_filter['feature_id']][] = $selected_filter['value_id'];
            }

            foreach ($grouped_filters as $feature_id => $value_ids) {
                $sql .= " AND (";
                $conditions = [];
                foreach ($value_ids as $index => $value_id) {
                    $conditions[] = "EXISTS (
                    SELECT 1
                    FROM shop_product_features spf
                    WHERE spf.product_id = p.id
                      AND spf.feature_value_id = :selected_value_id_{$feature_id}_{$index}
                )";
                    $params["selected_value_id_{$feature_id}_{$index}"] = $value_id;
                }
                $sql .= implode(' OR ', $conditions) . ")";
            }

            $sql .= " LIMIT 1";

            $result = $model->query($sql, $params)->fetch();
            $results[$feature_value_id] = !empty($result);
        }

        return $results;
    }

}
