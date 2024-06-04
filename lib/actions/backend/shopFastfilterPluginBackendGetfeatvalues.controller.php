<?php

class shopFastfilterPluginBackendGetfeatvaluesController extends waJsonController
{
    public function execute()
    {   
        $data = waRequest::get();
        $model = new shopFastfilterSettingsModel();
        $valueData = $model->getFeatValues($data['featid']);

        $this->response = array(
            'data' => $valueData 
        );
    }
}
