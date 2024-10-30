<?php
/*
Plugin Name: Bulk price
Plugin URI: http://www.hitaishin.com/
Description: Update Product base price and sale price in bulk percent.
Version: 1.0
Author: Hitaishin infotech
Auther uri : http://www.hitaishin.com/
*/
define( 'PLUGIN_PATH', plugins_url( __FILE__ ) );
add_action('admin_menu', 'bulk_price');
 
function bulk_price(){
    add_menu_page( 'Bulk price', 'Bulk price', 'manage_options', 'bulk-price', 'update_bulk_percent_woo_price' );
}
 
function update_bulk_percent_woo_price(){ 
	global $wpdb;
	$prefix = $wpdb->prefix;
/* bulk data start*/
if(isset($_POST['bulk_price1']))
{
	$very_price = sanitize_text_field($_POST['very_price']);
	$price_type = sanitize_text_field($_POST['price_type']);
	$percent = sanitize_text_field($_POST['percent']);
	$cat = sanitize_text_field($_POST['cat']);
	if(empty($very_price) || empty($price_type) || empty($percent) || empty($cat)){
		echo "All fields must required";
	}else{
		if($cat=='All')
		{
			$sql= "SELECT ".$prefix."posts.ID FROM ".$prefix."posts where post_type='product' AND post_status='publish' order by ".$prefix."posts.ID desc";
			$get_data=$wpdb->get_results($sql);
			if(!empty($get_data)){
				for($r=0;$r<count($get_data);$r++)
				{
					$upadte_r_price ='';	
					$regular_price= get_post_meta($get_data[$r]->ID, '_regular_price');
					$price= get_post_meta($get_data[$r]->ID, '_price');
					$sale_price= get_post_meta($get_data[$r]->ID, '_sale_price');
				if(!empty($price[0]))
				{
					if($price_type=='reguler_price')
					{
						if(!empty($price[0]))
						{
							$new_price= ($price[0]*$percent)/100;
							if($very_price=='decrease')
							{
							 	$upadte_r_price= $price[0]-$new_price;
							}
							else if($very_price=='increase')
							{

							 	$upadte_r_price= $price[0]+$new_price;
							}
							else{
							 	$upadte_r_price=$price[0];
							}
						}
					}
					else if($price_type=='base_price')
					{
						$new_price= ($price[0]*$percent)/100;	
							if($very_price=='decrease')
							{
							 	$upadte_r_price= $price[0]-$new_price;
							}
							else if($very_price=='increase')
							{
							 	$upadte_r_price= $price[0]+$new_price;
							}
							else{
							 	$upadte_r_price=$price[0];

							}
					}

					if($very_price=='increase'){
						update_post_meta($get_data[$r]->ID,'_price',$upadte_r_price);
						update_post_meta($get_data[$r]->ID,'_regular_price',$upadte_r_price);
						update_post_meta($get_data[$r]->ID,'_sale_price','');
					}else{					
							update_post_meta($get_data[$r]->ID,'_regular_price',$price[0]);
							update_post_meta($get_data[$r]->ID,'_sale_price',$upadte_r_price);
							update_post_meta($get_data[$r]->ID,'_price',$upadte_r_price);
						}	
				}

			}
				$date =date('Y-m-d H:i:s');
				echo "Price updated successfully";
			}
				else 
			{
				echo "No product found";
			}

		}
		else
		{
			$args = array('tag_ID' => $cat, 'post_type' => 'product');
			$get_data = get_posts( $args );
		//print_r($get_data); 
			if(!empty($get_data)){
				for($r=0;$r<count($get_data);$r++)
				{
					$upadte_r_price ='';	
					$regular_price= get_post_meta($get_data[$r]->ID, '_regular_price');
					$price= get_post_meta($get_data[$r]->ID, '_price');
					$sale_price= get_post_meta($get_data[$r]->ID, '_sale_price');

					if(!empty($price[0]))
					{
						if($price_type=='reguler_price')
						{
							if(!empty($price[0]))
							{
								$new_price= ($price[0]*$percent)/100;
								if($very_price=='decrease')
								{
								 	$upadte_r_price= $price[0]-$new_price;
								}
								else if($very_price=='increase')
								{

								 	$upadte_r_price= $price[0]+$new_price;
								}
								else{
								 	$upadte_r_price=$price[0];
								}
							}
						}
						else if($price_type=='base_price')
						{
							$new_price= ($price[0]*$percent)/100;
								if($very_price=='decrease')
								{
								 	$upadte_r_price= $price[0]-$new_price;
								}
								else if($very_price=='increase')
								{
								 	$upadte_r_price= $price[0]+$new_price;
								}
								else{
								 	$upadte_r_price=$price[0];

								}
						}
						if($very_price=='increase'){
							update_post_meta($get_data[$r]->ID,'_price',$upadte_r_price);
							update_post_meta($get_data[$r]->ID,'_regular_price',$upadte_r_price);
							update_post_meta($get_data[$r]->ID,'_sale_price','');
						}else{					
								update_post_meta($get_data[$r]->ID,'_regular_price',$price[0]);
								update_post_meta($get_data[$r]->ID,'_sale_price',$upadte_r_price);
								update_post_meta($get_data[$r]->ID,'_price',$upadte_r_price);
							}	
					}
				}
				$date =date('Y-m-d H:i:s');
				echo "Price updated successfully";
			}
			else 
			{
				echo "No product found";
			}
		}
	}

}
/* category wise data  end*/
?>
<br>

<h2>Update Bulk Price </h2>
<form method="post" action="">
	<select name="cat">
		<option value="All">All</option>
		<?php
 		$taxonomy     = 'product_cat';
  		$orderby      = 'name';  
  	$args = array(
    	     'taxonomy'     => $taxonomy,
        	 'orderby'      => $orderby,
       		);
		 $all_categories = get_categories( $args );
		 foreach ($all_categories as $cat) {
		    if($cat->category_parent == 0) {
		        $category_id = $cat->term_id;       
		        echo '<option value="'.$cat->cat_ID.'">'. $cat->name .'</option>';
		    }       
		}?>

	</select>
	<br>
		<input type="radio" name="price_type" value="base_price" checked>Base Price
		<input type="radio" name="price_type" value="reguler_price" >Regular Price
		<br>
		<br>
		<input type="radio" name="very_price" value="increase" checked>Increase
		<input type="radio" name="very_price" value="decrease">Decrease
		<br>
		<br>
		<input type="number" name="percent" maxlength="3" min="1"  onkeypress="return false" size="4"><span>%</span>
		<br>
		<br>
		<input type="submit" name="bulk_price1" value="Update Price">
</form>
<br>
<hr>
<?php }
?>