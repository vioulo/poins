<?php

0 目的：将 zencart 中的商品信息导入到 opencart
0 前提条件：zencart[ v1.5.5e]、opencart[3.0.3.2] 的数据库在同一台服务器 (亦或将查询结果保存成文件，之后在 opencart 的数据库中执行)
0 注意事项：同步图片的代码中并没有对数据做分页的处理，数据量很大的情况下请考虑分页

0 首先，清空下列 opencart 数据表 -> truncate table
| oc_category_description       |
| oc_category                   |
| oc_category_to_layout         |
| oc_category_to_layout         |

| oc_option                     |
| oc_option_description         |
| oc_option_value               |
| oc_option_value_description   |

| oc_product_attribute          |
| oc_product_discount           |
| oc_product_filter             |
| oc_product_recurring          |
| oc_product_related            |
| oc_product_reward             |
| oc_product_special            |
| oc_product_to_download        |

| oc_product                    |
| oc_product_description        |
| oc_product_image              | *
| oc_product_option             |
| oc_product_option_value       |

| oc_product_to_category        |
| oc_product_to_layout          |
| oc_product_to_store           |

0 接着在 opencart 数据库中执行以下 SQL，注意区分数据库(表)名称
# opencart category
insert into oc_category_description (category_id,language_id,name,meta_title,description,meta_description,meta_keyword)  select categories_id, 1, categories_name, categories_name, '', '','' from zcart0.categories_description;
insert into oc_category (category_id,top,`column`,status,date_added,date_modified) select category_id,0,1,1,'2021-06-09 09:19:53','2021-06-09 09:19:53' from oc_category_description;
insert into oc_category_to_layout (category_id,store_id,layout_id) select category_id,0,0 from oc_category;
insert into oc_category_to_store (category_id,store_id) select category_id,0 from oc_category;

# opencart product option
insert into oc_option (type,sort_order) values('radio',0);
insert into oc_option_description (option_id,language_id,name) values (1,1,'Size');
insert into oc_option_value_description (option_value_id,language_id,option_id,name) select products_options_values_id,1,1, products_options_values_name from zcart0.products_options_values;
delete from oc_option_value_description where option_value_id = 0;
insert into oc_option_value (option_value_id,option_id,image,sort_order) select option_value_id,1,'',0 from oc_option_value_description;

# opencart product
insert into oc_product (product_id,model,quantity,image,price,status,sku,upc,ean,jan,isbn,mpn,location,stock_status_id,manufacturer_id,tax_class_id,date_added,date_modified) select products_id,products_model,1000,CONCAT('zen_img/',products_image),products_price,1,'','','','','','','',7,0,0,products_date_added,products_last_modified from zcart0.products;
insert into oc_product_description (product_id,language_id,name,description,meta_title,tag,meta_description,meta_keyword) select products_id,1,products_name,products_description,products_name,'','','' from zcart0.products_description;
insert into oc_product_option (product_id,option_id,value,required) select product_id,1,'',1 from oc_product;
insert into oc_product_option_value (product_option_id,product_id,option_id,option_value_id,quantity,subtract,price,price_prefix,points,points_prefix,weight,weight_prefix) select product_option_id,product_id,option_id,options_values_id,100,1,0,'+',0,'+',0,'+' from zcart0.products_attributes as zpa left join ocart1.oc_product_option as opo on opo.product_id = zpa.products_id;
insert into oc_product_to_category select * from zcart0.products_to_categories;
insert into oc_product_to_layout select product_id,0,0 from oc_product;
insert into oc_product_to_store select product_id,0 from oc_product;

0 将 zencart 的商品图片复制到 opencart 的图片路径，保存在具体的文件夹中
|- cp -r zencart/images/\*  opencart/image/zen_img/
|- rm -r zen_img/[a-z]* (删除其他的图片，这里商品相关的图片文件夹都是大写)

0 将 opencart system/storage/modification 中的文件夹删除掉 (稍后再生成)

0 在 admin/controller/catalog/product.php 中添加以下代码
protected $zen_img = array();
protected $zen_top = '';
··· snippet ···
public function refresh_zimg() {

	$this->load->model('setting/setting');
	$check = $this->model_setting_setting->getSetting("refresh_zimg");
	$msg = '';

	if (!$check) {
		
		$this->load->model('catalog/product');

		$data = $this->model_catalog_product->getProductMainImages();
		$this->get_img_path(DIR_IMAGE."zen_img");
		
		$local_path = '';
		$query_path = '';
		$sql = '';

		foreach($this->zen_img as $z) {
			$local_path = substr($z, 0, strrpos($z, "."));
			foreach($data as $d) {
				$query_path = substr($d['image'], 0, strrpos($d['image'], "."));
				if (strstr($local_path, $query_path)) {
					$sql .= "({$d['product_id']}, '{$z}'),";
				}
			}
		}
		$sql = rtrim($sql, ',');
		
		$this->model_catalog_product->insertProductImages($sql);
		$msg = '操作已在执行，请稍后刷新商品页面查看';
		$this->model_setting_setting->insertSetting('refresh_zimg', 'is_done', 'yes');
	} else {

		$msg = '刷新操作已不再允许';
	}

	$this->response->addHeader('Content-Type: application/json');
	$this->response->setOutput(json_encode(array('msg' => $msg)));
}

protected function get_img_path($path) {

	$temp = scandir($path);

	foreach($temp as $v) {
		
		$child = $path . '/' . $v;
		if(is_dir($child)) {

			if($v == '.' || $v == '..') continue;
			$this->zen_top = $v;
			$this->get_img_path($child);
		} else {
			$this->zen_img[] = 'zen_img/'. $this->zen_top . '/' . $v;
		}
	}
}

0 在 admin/view/template/catalog/product_list.twig 的 '.page-header>.container-fluid>.pull-right"' 中添加以下代码
  {% if is_zen %}
	<button data-toggle="tooltip" title="刷新图片" cf-msg="确认刷新图片吗？" class="btn btn-info refresh-zimg"><i class="fa fa-refresh"></i></button>
	<script type="text/javascript">
	  $(".refresh-zimg").on("click", function() {
		
		if (!confirm($(this).attr("cf-msg"))) return;

		$.ajax({
			url: 'index.php?route=catalog/product/refresh_zimg&user_token={{ user_token }}',
			type: 'post',
			dataType: 'json',
			success: function(json) {
				alert(json.msg);
				$(".refresh-zimg").attr("cf-msg", "重复刷新将会产生垃圾数据！");
			}
		});
	  });
	</script>
  {% endif %}

0 在 admin/model/catalog/product.php 中添加以下代码
public function getProductMainImages() {
		
	$query = $this->db->query("SELECT product_id,image FROM " . DB_PREFIX . "product"); // 如果数据量很大，请考虑分页

	return $query->rows;
}

public function insertProductImages($string) {

	$query = $this->db->query("INSERT INTO " . DB_PREFIX . "product_image (product_id, image) VALUES {$string}");
}

0 在 admin/model/setting/setting.php 中添加以下代码
public function insertSetting($code = '', $key = '', $value = '', $store_id = 0) {

	if (!$code) return;

	$store_id = (int)$store_id;
	$code = $this->db->escape($code);
	$key = $this->db->escape($key);
	$value = $this->db->escape($value);

	$this->db->query("INSERT INTO " . DB_PREFIX . "setting (store_id,code,`key`,`value`,serialized) VALUES ({$store_id}, '{$code}', '{$key}', '{$value}', 0)");
}

0 然后，在后台商品列表页的链接后添加 `&zen=1`, 回车，会看到页面右上角的工具栏多了一个刷新按钮，点击按钮刷新图片配置
0 最后，在页面 `Extensions 》Modifications` 右上角的工具栏中点击刷新按钮，重新生成缓存文件