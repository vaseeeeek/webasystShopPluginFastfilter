<?php

class shopFastfilterPluginDeleteController extends waJsonController
{
    public function execute()
    {
        try {
            $id = waRequest::get('id', 0, 'int');

            if ($id) {
                $model = new shopFastfilterSettingsModel();
                $model->deleteSettings($id);

                $this->response = ['status' => 'success'];
            } else {
                throw new waException('Invalid ID');
            }
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }
    }
}
