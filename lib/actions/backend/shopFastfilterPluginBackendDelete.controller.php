<?php

class shopFastfilterPluginBackendDeleteController extends waJsonController
{
    public function execute()
    {
        // Получаем идентификатор записи для удаления из параметров запроса
        $id = waRequest::post('id', 0, 'int');

        // Проверяем, передан ли идентификатор
        if ($id > 0) {
            // Инициализируем модель
            $model = new shopFastfilterSettingsModel();

            // Удаляем запись
            $result = $model->deleteSettings($id);

            // Проверяем результат удаления
            if ($result) {
                // Успешно
                $this->response['status'] = 'ok';
                $this->response['message'] = 'Запись успешно удалена.';
            } else {
                // Ошибка при удалении
                $this->setError('Ошибка при удалении записи.');
            }
        } else {
            // Неверный идентификатор
            $this->setError('Неверный идентификатор записи.');
        }
    }
}
