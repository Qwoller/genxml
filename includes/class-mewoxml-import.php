<?php


class MeowImportXml
{

    public function __construct($version = '1.0', $encoding = 'UTF-8')
    {
        $this->xml = new \XMLWriter();
        $this->xml->openMemory();
        $this->xml->startDocument($version, $encoding);
    }


    public function importXml()
    {

        $this->xml->startElement('yml_catalog');
        $this->xml->writeAttribute('date', current_time('Y-m-d\TH:i'));

        $this->xml->startElement('shop');
        $this->xml->startElement('name');
        $this->xml->text('BEAUTY INSIDE');
        $this->xml->endElement();

        $this->xml->startElement('company');
        $this->xml->text('BEAUTY INSIDE');
        $this->xml->endElement();

        $this->xml->startElement('url');
        $this->xml->text('https://mybeautyinside.ru/');
        $this->xml->endElement();

        $this->xml->startElement('platform');
        $this->xml->text('WordPress - Yml for Yandex Direct');
        $this->xml->endElement();

        $this->xml->startElement('version');
        $this->xml->text('1.0.0');
        $this->xml->endElement();

        $this->xml->startElement('currencies');
        $this->xml->startElement('currency');
        $this->xml->writeAttribute('id', 'RUB');
        $this->xml->writeAttribute('rate', 1);
        $this->xml->endElement();
        $this->xml->endElement();

        $categories = get_categories([
            'taxonomy' => 'product_cat',
            'type' => 'post',
            'child_of' => 0,
            'parent' => '',
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => 1,
            'hierarchical' => 1,
            'exclude' => '',
            'include' => '',
            'number' => 0,
            'pad_counts' => false,
        ]);
        if ($categories) {
            foreach ($categories as $cat) {
                $allcat[$cat->term_id] = (array)$cat;
            }
        }


        $this->xml->startElement('categories');
        foreach ($allcat as $key => $cat) {
            $this->xml->startElement('category');
            $this->xml->writeAttribute('id', $cat['term_id']);
            if ($cat['category_parent'] != 0) {
                $this->xml->writeAttribute('parentId', $cat['category_parent']);
            }
            $this->xml->text($cat['name']);
            $this->xml->endElement();
            if ($cat['category_parent'] != 0) {
                $allcat[$cat['category_parent']]['childrens'][] = $cat;
                unset($allcat[$key]);
            }
        }
        $this->xml->endElement();

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );
        $query = new WP_Query;
        $allproducts = $query->query($args);


        $this->xml->startElement('offers');
        foreach ($allproducts as $allproduct) {
            $product_id = $allproduct->ID;
            $allproduct->url = get_permalink($product_id);

            $my_super_product = wc_get_product($product_id);
            if ($my_super_product->is_type('variable')) {
                $variations = $my_super_product->get_available_variations();
                foreach ($variations as $variation) {
                    $variation_obj = wc_get_product($variation['variation_id']);
                    $allproduct->old_price = $variation_obj->get_regular_price();
                    $allproduct->price = $variation_obj->get_sale_price();
                }
            }
            $allproduct->sku = $my_super_product->get_sku();
            echo '<pre>';
            var_dump($allproduct);
            echo '</pre>';
            $this->xml->startElement('offer');
            $this->xml->writeAttribute('id', $allproduct->sku);
            $this->xml->writeAttribute('available', 'true');

                $this->xml->startElement('url');
                $this->xml->text($allproduct->url);
                $this->xml->endElement();

                if (!empty($allproduct->price) and !empty($allproduct->old_price)) {
                    $this->xml->startElement('oldprice');
                    $this->xml->text($allproduct->old_price);
                    $this->xml->endElement();

                    $this->xml->startElement('price');
                    $this->xml->text($allproduct->price);
                    $this->xml->endElement();
                }

                $this->xml->startElement('currencyId');
                $this->xml->text('RUB');
                $this->xml->endElement();

                $this->xml->startElement('product_id');
                $this->xml->text($product_id);
                $this->xml->endElement();


                $product_categories = get_the_terms($product_id, 'product_cat');

                if ($product_categories && !is_wp_error($product_categories)) {
                    foreach ($product_categories as $category) {
                        // Выводим название категории
                        echo $category->name . '<br>';
                    }
                }
                foreach ($allproduct->categories as $cat) {
                    if(!empty($cat->term_id)) {
                        $this->xml->startElement('categoryId');
                        $this->xml->text($cat->term_id);
                        $this->xml->endElement();
                    }
                }

                $this->xml->startElement('vendor');
                $this->xml->text('BEAUTY INSIDE');
                $this->xml->endElement();

                $this->xml->startElement('description');
                $this->xml->text(strip_tags($allproduct->post_content));
                $this->xml->endElement();

                $this->xml->startElement('param');
                $this->xml->writeAttribute('name', 'Возраст');
                $this->xml->text('взрослый');
                $this->xml->endElement();

            $this->xml->endElement();// offer
        }
        $this->xml->endElement();// offers
        $this->xml->endElement();// shop
        $this->xml->endElement();// yml_catalog

        $upload_dir = wp_get_upload_dir();
        //print_r($upload_dir['basedir']);
        $xmlString = $this->xml->outputMemory();
        file_put_contents($upload_dir['basedir'] . '/yandex_direct.yml', $xmlString);
        return $xmlString;
    }


    public function importSale()
    {

        $this->xml->startElement('yml_catalog');
        $this->xml->writeAttribute('date', current_time('Y-m-d\TH:i'));

        $this->xml->startElement('shop');
        $this->xml->startElement('name');
        $this->xml->text('MEOW`ONE');
        $this->xml->endElement();

        $this->xml->startElement('company');
        $this->xml->text('MEOW`ONE');
        $this->xml->endElement();

        $this->xml->startElement('url');
        $this->xml->text('https://meowone.ru/');
        $this->xml->endElement();

        $this->xml->startElement('platform');
        $this->xml->text('WordPress - Yml for Yandex Direct');
        $this->xml->endElement();

        $this->xml->startElement('version');
        $this->xml->text('1.0.0');
        $this->xml->endElement();

        $this->xml->startElement('currencies');
        $this->xml->startElement('currency');
        $this->xml->writeAttribute('id', 'RUB');
        $this->xml->writeAttribute('rate', 1);
        $this->xml->endElement();
        $this->xml->endElement();

        $categories = get_categories([
            'taxonomy' => 'product_cat',
            'type' => 'post',
            'parent' => '',
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => 1,
            'hierarchical' => 1,
            'exclude' => '',
            'number' => 0,
            'pad_counts' => false,
        ]);
        if ($categories) {
            foreach ($categories as $cat) {
                if ($cat->term_id == 2142 || $cat->category_parent == 2142)
                    $allcat[$cat->term_id] = (array)$cat;
            }
        }

        $this->xml->startElement('categories');
        foreach ($allcat as $key => $cat) {
            $this->xml->startElement('category');
            $this->xml->writeAttribute('id', $cat['term_id']);
            if ($cat['category_parent'] != 0) {
                $this->xml->writeAttribute('parentId', $cat['category_parent']);
            }
            $this->xml->text($cat['name']);
            $this->xml->endElement();
            if ($cat['category_parent'] != 0) {
                $allcat[$cat['category_parent']]['childrens'][] = $cat;
                unset($allcat[$key]);
            }
        }

        $this->xml->endElement();
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => 2142,
                    'operator' => 'IN'
                ],
            ]
        );
        $query = new WP_Query;
        $allproducts = $query->query($args);
        $this->xml->startElement('offers');
        foreach ($allproducts as $allproduct) {

            $product_id = $allproduct->ID;
            $allproduct->url = get_permalink($product_id);
            $allproduct->gender = get_the_terms($product_id, 'pa_gender');
            $allproduct->type_product = get_the_terms($product_id, 'pa_type_product');
            $my_super_product = wc_get_product($product_id);
            if ($my_super_product->is_type('variable')) {
                $variations = $my_super_product->get_available_variations();
                foreach ($variations as $variation) {
                    $variation_obj = wc_get_product($variation['variation_id']);
                    $allproduct->old_price = $variation_obj->get_regular_price();
                    $allproduct->price = $variation_obj->get_sale_price();
                }
            }
            $allproduct->sku = $my_super_product->get_sku();
            if ($categorys = get_the_terms($allproduct->ID, 'product_cat')) {
                $allproduct = (array)$allproduct;
                foreach ($categorys as $cat) {
                    $cat = (array)$cat;
                    if ($cat['term_id'] == 2142 || $cat['parent'] == 2142) {
                        if ($cat['parent'] != 0) {
                            $allproduct['categories'][] = $cat;
                        } else {

                            $allproduct['main_categories'][] = $cat;
                        }
                    } else {

                    }



                }
            }

            $imgs = get_field("галерея_товара", $product_id);
            foreach ($imgs as $img) {
                $allproduct['img'][] = wp_get_attachment_image_src($img['изображение'], 'full');
            }
            $this->xml->startElement('offer');
            $this->xml->writeAttribute('id', $allproduct['sku']);
            $this->xml->writeAttribute('available', 'true');
            $this->xml->startElement('url');
            $this->xml->text($allproduct['url']);
            $this->xml->endElement();
            if (!empty($allproduct['price']) and !empty($allproduct['old_price'])) {
                $this->xml->startElement('oldprice');
                $this->xml->text($allproduct['old_price']);
                $this->xml->endElement();

                $this->xml->startElement('price');
                $this->xml->text($allproduct['price']);
                $this->xml->endElement();
            }
            if (empty($allproduct['price']) and !empty($allproduct['old_price'])) {
                $this->xml->startElement('price');
                $this->xml->text($allproduct['old_price']);
                $this->xml->endElement();
            }
            $this->xml->startElement('currencyId');
            $this->xml->text('RUB');
            $this->xml->endElement();

            $this->xml->startElement('product_id');
            $this->xml->text($product_id);
            $this->xml->endElement();


            foreach ($allproduct['categories'] as $cat) {
                if(!empty($cat['term_id'])) {
                    $this->xml->startElement('categoryId');
                    $this->xml->text($cat['term_id']);
                    $this->xml->endElement();
                }
            }
            foreach ($allproduct['img'] as $img) {
                $this->xml->startElement('picture');
                $this->xml->text($img[0]);
                $this->xml->endElement();
            }
            $this->xml->startElement('name');
            $this->xml->text($allproduct['post_title']);
            $this->xml->endElement();

            $this->xml->startElement('vendor');
            $this->xml->text('MEOW`ONE');
            $this->xml->endElement();

            $this->xml->startElement('description');
            $this->xml->text(strip_tags($allproduct['post_content']));
            $this->xml->endElement();

            $link = '';
            $model = '';

            foreach ($allproduct['type_product'] as $key => $type_product) {
                if ($key + 1 == count($allproduct['type_product'])) {
                    $link = $link . $type_product->name;
                } else {
                    $link = $link . $type_product->name . ' и ';
                }
            }
            $this->xml->startElement('typePrefix');
            $this->xml->text($link);
            $this->xml->endElement();

            foreach ($allproduct['gender'] as $key => $gender) {
                if ($key + 1 == count($allproduct['gender'])) {
                    $model = $model . $gender->name;
                } else {
                    $model = $model . $gender->name . ' и ';
                }
            }

            $this->xml->startElement('param');
            $this->xml->writeAttribute('name', 'Возраст');
            $this->xml->text('взрослый');
            $this->xml->endElement();
            $this->xml->endElement();// offer
        }
        $upload_dir = wp_get_upload_dir();
        $this->xml->endElement();// offers
        $this->xml->endElement();// shop
        $this->xml->endElement();// yml_catalog
        //print_r($upload_dir['basedir']);
        $xmlString = $this->xml->outputMemory();
        file_put_contents($upload_dir['basedir'] . '/yandex_direct_sale.yml', $xmlString);
        return $xmlString;
    }

}