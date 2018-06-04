<?php
class category{

    public static function getItems(){
        

        $autoparts = array();

        $pageN = 1;
        while ($page = hurl::tryLoad('https://autoparts.beforward.jp/search/SUZUKI/?page='.$pageN.'&limit=500&list_type=list')) {
            $html = new simple_html_dom();
            $html->load($page);

            $table = $html->find('table[class=sp-detail-table]');

            foreach ($html->find('table[class=sp-detail-table] tr') as $item) {
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
                    $autoparts[$refNo]['FullPrice'] = '';
                }
                foreach ($item->find('td[class=td-price] del') as $value) {
                    $autoparts[$refNo]['FullPrice'] = trim($value->plaintext);
                }
            }
            echo "Finished parsing page $pageN. CountItem: ".count($autoparts)." \n";

            $pageN++;
        }

        return $autoparts;
    }

    public static function setCategory(){
        $items = self::getItems();
        if (!$items) return false;
        stock::setCategory($items, 'suzuki_used_04062018');
    }

}