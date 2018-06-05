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

    public static function getListCat($url){
        $page = hurl::tryLoad($url);
        if (!$page) return false; 

        $html = new simple_html_dom();
        $html->load($page);

        $list = array();

        foreach ($html->find('div[class=pc-mode-block] section[class=side-nav-category] ul[class=list-child]') as $cat) {
        	foreach ($cat->find('a') as $value) {
        		$href = $value->href;
        		preg_match('/https:\/\/autoparts.beforward.jp\/search(.*)\?limit=20&direction=desc&sort=sort_year&new_old_type=U&list_type=list/', $href, $cat_herf);
        		$list[] = $cat_herf[1];
        	}
        }
        return $list;
    }
}