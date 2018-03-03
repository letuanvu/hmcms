# Hướng dẫn tạo giao diện căn bản

Tất cả các giao diện của HoaMai CMS nằm trong thư mục `hm_themes`, để bắt đầu tạo giao diện mới, bạn cần tạo 1 thư mục mới tại đây để chứa giao diện của bạn, tên thư mục viết thường và không chứa dấu cách hay các ký tự đặc biệt hay dấu tiếng việt.

Trong ví dụ này ta tạo một thư mục có tên là **mytheme**:

![](https://raw.githubusercontent.com/manhnam91/hmcms/master/docs/images/create-simple-theme/1.png)

Trong một giao diện, phần bắt buộc bạn phải tạo là file **init.php**, đây là file chứa thông tin về giao diện cũng như các function bạn cần dùng cho giao diện của mình. Đây là file được load đầu tiên của giao diện.
Nội dung file bạn có thể khai báo thông tin về giao diện theo cấu trúc sau, thường ở đầu file.

```
/*
Theme Name: Giao diện của tôi;
Description: Đây là một giao diện cho HoaMai CMS;
Version: 1.0;
Version Number: 1;
*/
```

Trong đó

* Theme Name: Tên giao diện của bạn, bạn có thể đặt tên theo ý bạn.
* Description: Mô tả giao diện của bạn.
* Version: Tên của phiên bản, có thể là chữ hoặc số.
* Version Number: Thứ tự của phiên bản, bắt buộc là dạng số.

`Lưu ý, khi kết thúc mỗi dòng bạn phải có dấu chấm phẩy (;).`

Lúc này tại trang **Giao diện khả dụng**, giao diện của bạn đã xuất hiện, bạn có thể kích hoạt nó ngay để thuận tiện cho việc debug trong quá trình làm.

![](https://raw.githubusercontent.com/manhnam91/hmcms/master/docs/images/create-simple-theme/2.png)

Sau khi kích hoạt, truy cập trang web bạn sẽ thấy báo lỗi như sau:

![](https://raw.githubusercontent.com/manhnam91/hmcms/master/docs/images/create-simple-theme/3.png)

và bây giờ chúng ta sẽ tạo trang chủ của giao diện bằng cách tạo 1 file index.php trong thư mục giao diện, trong ví dụ này chúng ta sẽ viết nội dung cho file index.php là:

`Chào mừng bạn đến với giao diện của tôi`

![](https://raw.githubusercontent.com/manhnam91/hmcms/master/docs/images/create-simple-theme/4.png)

và sau khi ra ngoài f5 lại trang web bạn sẽ thấy nội dung trong index.php xã xuất hiện

Chúng ta đã biết cách để tạo giao diện cho trang chủ, nhưng trong website luôn có những phần cố định được sử dụng ở toàn trang, như header và footer hay side bar.

Chúng ta sẽ tạo tiếp 2 file **header.php** và **footer.php** ngang hàng **index.php**
Với nội dung file **header.php**:

```
<body>
	<header>
		<h1>Header</h1>
		<hr>
	</header>
```

và **footer.php**:

```
	<footer>
		<hr>
		<h1>Footer</h1>
	</footer>
</body>
```

Sửa lại **index.php** như sau:

```
<?php  
get_template_part('header');  
?>  
<article>  
 <span class="Apple-tab-span" style="white-space:pre"></span> <h1>Chào mừng bạn đến với giao diện của tôi</h1>  
</article>  
<?php  
get_template_part('footer');  
?>
```

Sau đó f5 lại trang web, bạn sẽ thấy xuất hiện:

![](https://raw.githubusercontent.com/manhnam91/hmcms/master/docs/images/create-simple-theme/5.png)

Như vậy để gọi một file giao diện vào bạn sử dụng hàm **get_template_part('ten-file');**

bạn cũng có thể để riêng trong 1 thư mục, ví dụ:**get_template_part('block/header');**

Tiếp tục để gọi các file css và js chúng ta sử dụng các hàm sau:

**css()**

Đây là function dùng để nhúng nhanh 1 tệp tin css, bạn sử dụng bằng cách

**echo css('ten-file.css');**

kết quả trên giao diện sẽ sinh ra mã:

`<link rel="stylesheet" type="text/css" href="ten-mien.com/hm_themes/thu-muc-giao-dien/ten-file.css">`

ví dụ: **echo css('style.css');** hoặc **echo css('css/style.css');** nếu file css ở trong thư mục css.

Tương tự như function **css()**, chúng ta có các function thường dùng khác:

**js()**: Để nhúng file javascript (.js);

**img()**: để nhúng 1 file ảnh;

các hàm trên ngoài tên file bạn có thể truyền thêm 1 mảng chứa các attribute, ví dụ như:

**echo img('myimg.jpg',array('id'=>'myimg','alt'=>'alt_of_img'))**;

Ngoài ra trong thẻ <head> của giao diện bạn cần sử dụng hàm **hm_head()**;

Đây là hàm hiển thị thẻ **<title>** và các thẻ **<meta>** của trang, ngoài ra các plugin hay giao diện dùng hook của hàm **hm_head()** để chèn thêm css, js hay các thẻ khác vào trong **<head>**, vì vậy đây là hàm quan trọng và **gần như bắt buộc** khi bạn viết một theme

ví dụ đây là một phần của file **header.php** mẫu:

```
<!DOCTYPE html>
<html lang="en">
<head>
	<?php hm_head(); ?>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap Core CSS -->
	<?php echo css('asset/css/bootstrap.css'); ?>
	<!-- Custom CSS -->
	<?php echo css('asset/css/menu.css'); ?>
	<?php echo css('asset/css/style.css'); ?>
</head>
```

Tiếp theo chúng ta sẽ tìm hiểu các lấy vài viết ra ngoài giao diện. Để làm được điều này bạn cần hiểu khái niệm *content và taxonomy*, có thể tham khảo :

[Cơ bản về việc sử dụng custom content và custom taxonomy](https://github.com/manhnam91/hmcms/blob/master/docs/sub-page/custom-content-and-custom-taxonomy.md)

Trong ví dụ này chúng ta sẽ làm việc với kiểu nội dung mặc định là `post` và kiểu phân loại `category`

Tại trang **index.php** đã tạo, bạn thêm hàm **query_content()**

Lấy dữ liệu trả về từ hàm này vào biến `$ids` và `print_r` để xem kết quả, nội dung **file index.php** hiện tại như sau:

```
<?php
get_template_part('header');
?>
<article>
	<h1>Chào mừng bạn đến với giao diện của tôi</h1>
	<pre>
	<?php
	$ids = query_content();
	print_r($ids);
	?>
	</pre>
</article>
<?php
get_template_part('footer');
?>
```

Sau khi tải lại trang web bạn sẽ thấy kết quả như sau:

![](https://raw.githubusercontent.com/manhnam91/hmcms/master/docs/images/create-simple-theme/6.png)

Vì bạn gọi hàm **query_content()** tại trang `index.php` nên nó sẽ trả về 1 chuỗi id các bài viết, với giới hạn trả về là số bài bạn đã cài đặt cho phần `Số bài trên 1 trang` trong trang `Cài đặt tổng quan` và sắp xếp theo thời gian đăng bài, bài mới lên trước.

Việc còn lại bạn chỉ cần `foreach` mảng các `id` bài viết để tạo 1 list các bài viết tại trang chủ.

Từ `id` bài viết bạn có thể lấy giá trị các `field` của bài viết bằng hàm **get_con_val()**

Trong ví dụ này sử dụng conten type `bài viết` mặc định của HoaMai CMS, dạng nội dung này có các field sau:

* name : Tên bài viết
* description : Mô tả bài viết
* content : Nội dung bài viết

Vậy để lấy `tên bài viết` theo id bài ta sẽ làm như sau:

`get_con_val("name=name&id=$id");`

Hoặc cách viết khác:

`get_con_val(array('name'=>'name', 'id'=>$id));`

Với hàm `get_con_val()` bạn có thể lấy giá trị tất cả các field của bài viết theo id, các field này bạn `có thể khai báo trong lúc tạo 1 custom content mới`, hoặc bạn muốn thêm field cho dạng bài viết mặc định thì có thể sửa file `hm_setup.php`

Để lấy đường dẫn đến theo id bài viết chúng ta dùng hàm **request_uri()**

Đối với ví dụ trên chúng ta sẽ sử dụng:

`request_uri("type=content&id=$id");`

Hoặc bạn cũng có thể sử dụng kiểu truyền vào là `array` giống hàm **get_con_val()**

Sửa lại file **index.php** hoàn chỉnh như sau:

```
<?php
get_template_part('header');
?>
<article>
	<h1>Chào mừng bạn đến với giao diện của tôi</h1>
	<?php
	$ids = query_content();
	foreach($ids as $id){
		$name = get_con_val("name=name&id=$id");
		$link = request_uri("type=content&id=$id");
		echo '<a href="' . $link . '"> ' . $name . ' </a><br/>';
	}
	?>
</article>
<?php
get_template_part('footer');
?>
```

Lúc này chúng ta có thể thấy kết quả như sau:

![](https://raw.githubusercontent.com/manhnam91/hmcms/master/docs/images/create-simple-theme/7.png)

Về phần danh mục chúng ta tạo 1 danh mục ví dụ là : `Danh mục 1` , sau đó trong thư mục giao diện tạo file **taxonomy.php** với nội dung như sau :

```
<?php
get_template_part('header');
?>
<article>
	<h1><?php echo get_tax_val("name=name&id=$id"); ?></h1>
	<?php
	$ids = query_content();
	foreach($ids as $id){
		$name = get_con_val("name=name&id=$id");
		$link = request_uri("type=content&id=$id");
		$content_thumbnail = get_con_val("name=content_thumbnail&id=$id");
		$img = create_image("file=$content_thumbnail&w=300&h=200");
		echo '<a href="' . $link . '"> <img src="' . $img . '" alt="" >' . $name . ' </a>';
	}
	?>
</article>
<?php
get_template_part('footer');
?>
```

Như các bạn đã thấy nội dung của file này gần giống với file **index.php** dùng xử lý giao diện trang chủ. File này sẽ tự nhận biến `$id` là `id của taxonomy bạn đang truy cập`, ở đây là id của Danh mục 1. Hàm **get_tax_val()** có tác dụng tương tự như hàn **get_con_val()** đã nói ở phần trước, dùng để lấy giá trị của `taxonomy` theo tên field và id, trong ví dụ trên dùng để lấy ra tên của danh mục.

Hàm **create_image()** dùng để tạo 1 ảnh từ `id của ảnh đó`, bạn có thể truyền vào tham số độ rộng (w) và độ cao (h) hoặc chất lượng ảnh (q), ảnh mới sẽ được cắt từ ảnh gốc theo cỡ bạn đã truyền vào.

Về phần vòng lặp các bài viết trong danh mục đó các bạn viết như với **index.php**, chỉ khác hàm **query_content()** trong **index.php** sẽ return toàn bộ id content, còn nếu viết trong **taxonomy.php** như ví dụ này thì chỉ return lại id các content thuộc danh mục đó, và khi truy cập danh mục này ta sẽ có kết quả:

![](https://raw.githubusercontent.com/manhnam91/hmcms/master/docs/images/create-simple-theme/8.png)

Ngoài ra trong trang này bạn có thể sử dụng một số hàm sau:

* **breadcrumb();**: Hiển thị breadcrumb
* **pagination();**: Hiển thị phân trang (pagination)

Để tạo trang chi tiết bài viết, bạn tạo 1 file **content.php** với nội dung như sau:

```
<?php
get_template_part('header');
?>
<article>
	<h1 class="post-title"><?php echo get_con_val("name=name&id=$id"); ?></h1>
	<div>
		<?php echo get_con_val("name=content&id=$id"); ?>
	</div>
</article>
<?php
get_template_part('footer');
?>
```

và sau khi truy cập đường dẫn bài viết sẽ có kết quả:

![](https://raw.githubusercontent.com/manhnam91/hmcms/master/docs/images/create-simple-theme/9.png)

Tương tự như với file **taxonomy.php**, biến `$id` trong **content.php** đã mặc định được gán giá trị là `id của bài viết đang xem`, các bạn dựa vào id này để lấy giá trị các trường của bài viết, như trong ví dụ trên là lấy ra tên và nội dung.

Để có thể sử dụng được menu trong giao diện trước tiên bạn cần đăng ký menu đó bằng hàm **register_menu_location()**, mở file **init.php** trong thư mục giao diện và thêm đoạn code sau:

```
/** Đăng ký vị trí menu đầu trang */
$args = array(
			'name'			=>'topmenu',
			'nice_name' 		=> _('Menu đầu trang'),
			'wrapper' 		=> 'ul',
			'wrapper_class' 	=> ''
			'wrapper_id' 		=> '',
			'item' 			=> 'li',
			'item_class' 		=> '',
			'item_id' 		=> '',
			'permalink_class' 	=> '',
			'permalink_attr' 	=> '',
			'permalink_before'	=> '',
			'permalink_after'	=> '',
			'echo'			=> FALSE,
		);
register_menu_location($args);
```
Trong đó:

* **name**: là key duy nhất của menu, không trùng với menu khác.

* **nice_name**: là tên của menu đó.

* **wrapper**: thẻ bao ngoài của menu, nếu bạn để trống mặc định sẽ là thẻ <ul>.

* **wrapper_class** và **wrapper_id**: id và class của thẻ bao ngoài dùng cho css.

* **item**: thẻ bao ngoài của một phần tử menu, mặc định là thẻ <li>.

* **item_class** và **item_id**: tương tự là class và id của một phần tử trong menu.

* **permalink_class**: class của đường link trong menu item, dùng cho css, sẽ thêm vào thẻ <a>.

* **permalink_attr**: các attributes của đường link, bạn có thể ghi thêm ví dụ như `onclick="menu_click();"`. data-color="red" ..., dùng nếu bạn cần áp dụng javascript cho menu.

* **permalink_before**: thẻ bao quanh đường link của menu_item, ví dụ bạn có thể khai báo là <span class="menu_link">.

* **permalink_after**: thẻ đóng của thẻ bạn đã khai báo trong permalink_before.

* **echo**: echo menu ra giao diện, nếu để mặc định FALSE thì trong giao diện bạn sẽ phải echo menu ra.

Sau khi khai báo menu, để menu hiển thị được trong giao diện bạn sử dụng lệnh:

echo **menu_location('topmenu')**;

trong đó `topmenu` là `key của menu mà bạn đã khai` báo lúc dùng hàm: **register_menu_location()**.

Bắt đầu từ phiên bản 1.1.4 HoaMai CMS được nâng cấp thêm tính năng block, đây là tính năng dành cho nhà phát triển tạo ra các theme `có tính tùy biến cao hơn` so với việc sử dụng trang cài đặt giao diện trong admincp.

Tại menu Giao diện => Kéo thả bố cục bạn sẽ thấy hỗ trợ sẵn 3 block đơn giản là: Văn bản, hình ảnh và trình đơn.

![](https://raw.githubusercontent.com/manhnam91/hmcms/master/docs/images/create-simple-theme/10.png)

Tuy nhiên bạn cần tạo vị trí để kéo thả chúng vào giao diện, được gọi là `Block Container`. Để làm việc này trong file **init.php** của giao diện bạn dùng hàm **register_block_container()** để tạo 1 vị trí kéo thả:

```
$args = array(
			'name'			=>'homeblock',
			'nice_name' 		=> _('Khối trang chủ'),
		);
register_block_container($args);
```

Sau đó quay lại trang kéo thả giao diện bạn sẽ thấy `Block Container` mà bạn đã tạo, kéo thả thử vài `Block` vào vị trí đó

![](https://raw.githubusercontent.com/manhnam91/hmcms/master/docs/images/create-simple-theme/11.png)

Bạn có thể kéo thả lên xuống để xếp vị trí các Block, `bấm vào tên Block` để thu gọn hoặc mở phần cài đặt của Block đó.

Tại phía ngoài giao diện, bạn cần gọi vị trí bạn muốn hiển thị các Block này bằng hàm :

**block_container('ten_block')**;

Trong ví dụ này chúng ta đã tạo Block Container `tên là homeblock`, nên tại giao diện ta gọi:

**<?php block_container('homeblock'); ?>**

và sẽ thấy các Block xuất hiện:

![](https://raw.githubusercontent.com/manhnam91/hmcms/master/docs/images/create-simple-theme/12.png)

Bạn có thể tạo nhiều Block Container khác nhau tùy vào giao diện của bạn, và tất nhiên `bạn có thể tạo ra các Block khác của riêng bạn`. Ví dụ như tại trang chủ có các khối danh mục, bạn muốn biến các khối này thành các Block để kéo thả vị trí, thêm hoặc xóa được linh động hơn, bạn có thể vào lại **init.php** của giao diện và tạo thêm Block bằng đoạn code như sau:

```
function homecatblockgrid($block_id){
	$cat_id = get_blo_val(array('name'=>'cat_id','id'=>$block_id));
	$cat_name = get_blo_val(array('name'=>'cat_name','id'=>$block_id));
	$num_product = get_blo_val(array('name'=>'product_number','id'=>$block_id));
	echo 'Block '.$cat_name.' có id là '.$cat_id.' và muốn hiện ra '.$num_product.' bài  <br>';
}

$args = array(
			'name'		=> 	'homecatblockgrid',
			'nice_name' 	=> 	_('List bài từ danh mục'),
			'iuput'		=> 	array(
							array(
								'nice_name'=>'Tên khối danh mục',
								'name'=>'cat_name',
								'input_type'=>'text',
								'required'=>TRUE,
							),
							array(
								'nice_name'=>'Số bài hiển thị',
								'default_value'=>'12',
								'name'=>'product_number',
								'input_type'=>'number',
								'required'=>TRUE,
							),
							array(
								'nice_name'=>'Chọn danh mục',
								'name'=>'cat_id',
								'input_type'=>'taxonomy_select',
								'data_key'=>array('category'),
								'required'=>FALSE,
							),
						),
			'function'	=> 	'homecatblockgrid',
		);
register_block($args);
```

Quay lại trang kéo thả giao diện và kéo vào block trên

![](https://raw.githubusercontent.com/manhnam91/hmcms/master/docs/images/create-simple-theme/13.png)

và kết quả:

![](https://raw.githubusercontent.com/manhnam91/hmcms/master/docs/images/create-simple-theme/14.png)

Hàm **register_block()** khi sử dụng cần đủ các phần tử sau của mảng truyền vào:

* **name**: tên Block, viết thường không dấu và liền mạch

* **nice_name**: Tên hiển thị trong admin

* **input**: Mảng các input của block này

* **function**: Hàm thực thi Block, trong hàm này bạn sử dụng **get_blo_val()** để gọi các giá trị của input mà bạn tạo cho Block này, hàm này do bạn tạo ra nhưng luôn phải theo cấu trúc : **ten_ham_cua_ban($block_id)**; tức là luôn phải có input **$block_id** mặc dù biến này sẽ được tự sinh ra khi bạn kéo thả Block trong admin và gán cho từng Block, biến này cũng dùng cho hàm **get_blo_val()** để lấy chính xác các giá trị của Block đó.

Trong ví dụ trên bạn có thể thấy cách lấy các giá trị của Block, tuy nhiên việc bạn viết vòng lặp **query_content()** hay html vào trong hàm hiển trị của block sẽ rất rối code và khó chỉnh sửa sau này. Vì vậy chúng tôi khuyến khích bạn `nên gọi template giao diện và truyền các input vào`, đối với ví dụ trên ta có thể dùng:

```
function homecatblockgrid($block_id){
	$cat_id = get_blo_val(array('name'=>'cat_id','id'=>$block_id));
	$cat_name = get_blo_val(array('name'=>'cat_name','id'=>$block_id));
	$num_product = get_blo_val(array('name'=>'product_number','id'=>$block_id));
	get_template_part("block-list-post", array('cat_name'=>$cat_name,'cat_id'=>$cat_id,'num_product'=>$num_product,'block_id'=>$block_id));
}
```

Và bây giờ trong thư mục chứa giao diện bạn chỉ cần tạo file **block-list-post.php** và dùng sẵn các biến đã truyền vào ở trên để tạo ra template list các bài viết theo danh mục.
