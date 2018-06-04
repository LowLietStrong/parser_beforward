<?php
class stock{

    public static function setCategory($items, $cat){
        $db = new db();

        foreach ($items as $item){
            $sql = sprintf("INSERT INTO %s (RefNo, GenuineNo, Name, PicURL, URL, Category,  Manufacturer, Model, ModelCode, CurentPrice, FullPrice) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                $cat,$item['RefNo'],$item['GenuineNo'],$item['Name'],$item['PicURL'],$item['URL'],$item['Category'],$item['Manufacturer'],$item['Model'],$item['ModelCode'],$item['CurentPrice'],$item['FullPrice']);
            $db->dbQuery($sql);
        }

        $db->destroy();
    }
}