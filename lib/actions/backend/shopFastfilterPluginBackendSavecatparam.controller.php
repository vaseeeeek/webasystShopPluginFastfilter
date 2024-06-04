<?php

class shopFastfilterPluginBackendSavecatparamController extends waJsonController
{
    public function execute()
    {
        // Получаем данные из запроса
        $data = waRequest::get();
        
        // Проверяем наличие необходимых данных
        if (empty($data['feature_values']) || empty($data['category_id']) || empty($data['feature_id'])) {
            $this->setError('Invalid data');
            return;
        }

        // Декодируем JSON-данные
        $feature_values = json_decode($data['feature_values'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->setError('Invalid JSON data');
            return;
        }

        // Создаем экземпляр модели
        $model = new shopFastfilterSettingsModel();
        
        // Сохраняем настройки
        $success = $model->saveSettings($data['category_id'], $data['feature_id'], $data['feature_code'], $data['feature_name'], $feature_values);

        if ($success) {
            $this->response = array(
                'status' => 'success',
                'data' => $data
            );
        } else {
            $this->setError('Failed to save settings');
        }
    }
}
