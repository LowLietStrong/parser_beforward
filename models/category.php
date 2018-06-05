<?php
class category{

    public static function getItems($cat_href){

        $autoparts = array();

        $pageN = 1;
        while ($page = hurl::tryLoad('https://autoparts.beforward.jp/search'.$cat_href.'?page='.$pageN.'&limit=500&list_type=list&new_old_type=U')) {
            $flag_end_list = true;

            $html = new simple_html_dom();
            $html->load($page);
            foreach ($html->find('header[class=filter-options] h2') as $value) {
                $item_on_page = preg_replace('/items found/', '', $value->plaintext);
            }

            foreach ($html->find('table[class=sp-detail-table] tr') as $item) {
                $flag_end_list    = false;
                $flag_broken_item = true;

                foreach ($item->find('td[class=td-ref]') as $value) {
                    $ref = $value->innertext;
                    $ref = explode('<br>', $ref);

                    $refNo = $ref[0];
                    $autoparts[$refNo]['RefNo']     = trim($ref[0]);
                    $autoparts[$refNo]['GenuineNo'] = trim($ref[1]);
                }
                foreach ($item->find('td[class=td-name] a') as $value) {
                    $autoparts[$refNo]['Name'] = trim($value->plaintext);
                    $autoparts[$refNo]['URL'] = $value->href;
                }
                foreach ($item->find('td[class=td-img] img') as $value) {
                    $autoparts[$refNo]['PicURL'] = 'https:'.$value->src;
                }
                foreach ($item->find('td[class=td-cat]') as $value) {
                    $autoparts[$refNo]['Category'] = trim($value->plaintext);
                }
                foreach ($item->find('td[class=td-make]') as $value) {
                    $autoparts[$refNo]['Manufacturer'] = trim($value->plaintext);
                }
                foreach ($item->find('td[class=td-model]') as $value) {
                    $autoparts[$refNo]['Model'] = trim($value->plaintext);
                }
                foreach ($item->find('td[class=td-model-code]') as $value) {
                    $autoparts[$refNo]['ModelCode'] = trim($value->plaintext);
                }
                foreach ($item->find('td[class=td-price] span[class=list-price]') as $value) {
                    $autoparts[$refNo]['CurentPrice'] = trim($value->plaintext);
                    $autoparts[$refNo]['FullPrice']   = '';
                    $flag_broken_item = false;
                }
                foreach ($item->find('td[class=td-price] del') as $value) {
                    $autoparts[$refNo]['FullPrice'] = trim($value->plaintext);
                }
                if ($flag_broken_item AND isset($refNo)) {
                    unset($autoparts[$refNo]);
                }
            }
            if ($flag_end_list) {
                echo "End list. Total: ".count($autoparts)." of $item_on_page\n";
                break;
            }
            echo "$cat_href CountItem: ".count($autoparts)." of $item_on_page\n";

            $pageN++;
        }

        return $autoparts;
    }

    public static function setAllCategoryByModel(){
        $list_cat = stock::getListCat('https://autoparts.beforward.jp/search/SUZUKI/?new_old_type=U');
        foreach ($list_cat as $cat_href) {
            $items = self::getItems($cat_href);
            if (!$items) continue;
            stock::setCategory($items, 'suzuki_used_05062018');
        }

    }



}