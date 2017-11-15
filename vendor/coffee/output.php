<?php

namespace coffee;

use services\config , services\interpret;

class output
{
    private static function restrictionStructure($code = 0 , $note = '', $data = [])
    {
        ob_clean();

        $restrictionStructure = config::get('app.api.structure') ? : [
            'code_name'=>'code',
            'note_name'=>'note',
            'data_name'=>'data'
        ];

        return [
            $restrictionStructure['code_name']=>$code,
            $restrictionStructure['note_name']=>interpret::language($note),
            $restrictionStructure['data_name']=>$data
        ];
    }

    private function addDataToNode(\SimpleXMLElement $node, $data)
    {
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = "unknownNode_". (string) $key;
            }
            $key = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key);
            if (is_array($value)) {
                $child = $node->addChild($key);
                $this->addDataToNode($child, $value);
            } else {
                $value = str_replace('&', '&amp;', print_r($value, true));
                $node->addChild($key, $value);
            }
        }
        return $node;
    }

    public function xml($note = 'success' , $code = 0 , $data = [])
    {
        $node = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><root />");

        return $this->addDataToNode($node, $this->restrictionStructure($code,$note,$data))->asXML();
    }

    public function json($note = 'success' , $code = 0 , $data = [])
    {
        return json_encode($this->restrictionStructure($code,$note,$data));
    }

    public function error( $code = 500 ,  $note = 'error.' , $data = [])
    {
        if(config::get('debug.switch'))
        {
            return $this->restrictionStructure($code , $note , $data);
        }
        else
        {
            return $this->restrictionStructure($code , config::get('debug.message')? : $note, []);
        }
    }
}