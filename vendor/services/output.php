<?php

namespace services;

class output
{
    public static function json($code = 0 , $note = '', $data = [])
    {
        $restrictionStructure = config::get('restriction_structure') ? : [
            'code_name'=>'code',
            'note_name'=>'note',
            'data_name'=>'data'
        ];

        return [
            $restrictionStructure['code_name']=>$code,
            $restrictionStructure['note_name']=>$note,
            $restrictionStructure['data_name']=>$data
        ];
    }
}