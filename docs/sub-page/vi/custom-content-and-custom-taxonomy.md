# Cơ bản về việc sử dụng custom content và custom taxonomy

HM có sẵn tính năng viết bài và phân loại bài viết theo danh mục. Bạn có thể phân chia nội dung của mình theo từng danh mục tương ứng, hoặc 1 bài viết nằm trong nhiều danh mục. Tuy nhiên nếu cảm thấy điều này chưa đủ bạn có thể tạo thêm 1 kiểu nội dung riêng, với kiểu phân loại riêng để tiện cho việc quản lý.

Để làm việc này chúng ta dùng 2 hàm : **register_content()** và **register_taxonomy()**

Bạn có thể dùng 2 hàm này để tạo kiểu nội dung mới ở các vị trí:

1.  File init.php trong thư mục giao diện : Chỉ có tác dụng khi bạn kích hoạt và sử dụng giao diện đã khai báo kiểu nội dung tại init.php
2.  File hm_setup.php trong thư mục mã nguồn : Có tác dụng với bất kỳ giao diện nào, tất nhiên bạn phải viết thêm file giao diện để hiển thị kiểu nội dung riêng này, ( Thực chất kiểu nội dung "bài viết" và phân loại "danh mục" mặc định của mã nguồn đã được khai báo tại đây ).
3.  Trong một plugin : Có tác dụng khi bạn kích hoạt plugin.

Ví dụ về tạo content sản phẩm và taxonomy danh mục sản phẩm. Trong ví dụ sau đây, bạn có thể viết đoạn mã này vào hm_setup.php và lưu lại.

```
/** Tạo danh mục sản phẩm */
$field_array=array(
	'primary_name_field'=>array(
		'nice_name'=>'Tên danh mục',
		'description'=>'Danh mục dùng phân loại sản phẩm của bạn',
		'name'=>'name',
		'create_slug'=>TRUE,
		'input_type'=>'text',
		'default_value'=>'',
		'placeholder'=>'Nhập tên danh mục',
		'required'=>TRUE,
	),
	array(
		'nice_name'=>'Mô tả danh mục sản phẩm',
		'description'=>'Một đoạn văn bản ngắn mô tả về danh mục sản phẩm này',
		'name'=>'description',
		'input_type'=>'textarea',
		'default_value'=>'',
		'placeholder'=>'',
		'required'=>FALSE,
	),
);

$args=array(
	'taxonomy_name'=>'Danh mục sản phẩm',
	'taxonomy_key'=>'product-category',
	'content_key'=>'product',
	'all_items'=>'Tất cả danh mục',
	'edit_item'=>'Sửa danh mục',
	'view_item'=>'Xem danh mục',
	'update_item'=>'Cập nhật danh mục',
	'add_new_item'=>'Thêm danh mục mới',
	'new_item_name'=>'Tên danh mục mới',
	'parent_item'=>'Danh mục cha',
	'no_parent'=>'Không có danh mục cha',
	'search_items'=>'Tìm kiếm danh mục',
	'popular_items'=>'danh mục dùng nhiều',
	'taxonomy_field'=>$field_array,
	'primary_name_field'=>$field_array['primary_name_field'],
);
register_taxonomy($args);

/** Tạo post type sản phẩm */
$field_array=array(
	'primary_name_field'=>array(
		'nice_name'=>'Tên sản phẩm',
		'name'=>'name',
		'create_slug'=>TRUE,
		'input_type'=>'text',
		'default_value'=>'',
		'placeholder'=>'Tiêu đề sản phẩm',
		'required'=>TRUE,
	),
	array(
		'nice_name'=>'Giá bán',
		'name'=>'price',
		'input_type'=>'number',
		'default_value'=>'',
		'placeholder'=>'Chỉ nhập số',
	),
	array(
		'nice_name'=>'Tình trạng',
		'name'=>'product_status',
		'input_type'=>'select',
		'input_option'=>array(
					array('value'=>'in-stock','label'=>'Còn hàng'),
					array('value'=>'out-of-stock','label'=>'Hết hàng'),
					array('value'=>'place-an-order','label'=>'Nhận Order'),
			),
		'required'=>TRUE,
	),
	array(
		'nice_name'=>'Chi tiết sản phẩm',
		'name'=>'content',
		'input_type'=>'wysiwyg',
		'default_value'=>'',
		'placeholder'=>'',
		'required'=>FALSE,
	),

);

$args=array(
	'content_name'=>'Sản phẩm',
	'taxonomy_key'=>'product-category',
	'content_key'=>'product',
	'all_items'=>'Tất cả sản phẩm',
	'edit_item'=>'Sửa sản phẩm',
	'view_item'=>'Xem sản phẩm',
	'update_item'=>'Cập nhật sản phẩm',
	'add_new_item'=>'Thêm sản phẩm mới',
	'new_item_name'=>'Tên sản phẩm mới',
	'search_items'=>'Tìm kiếm sản phẩm',
	'content_field'=>$field_array,
	'primary_name_field'=>$field_array['primary_name_field'],
);
register_content($args);

```

Trong biến **$field_array**, chứa các trường dùng trong content và taxonomy sắp tạo phải có 1 phần tử có key là là **primary_name_field**, đây là điều <span style="color: #ff0000;">bắt buộc</span>. phần tử này là trường được dùng để tạo ra đường dẫn tĩnh, thường nó sẽ là trường **name** - tên.

Để tạo mối liên hệ giữa 1 custom content và 1 custom taxonomy thì phần tử của mảng truyền vào : **content_key** và **taxonomy_key** phải trùng nhau như ví dụ trên.

File giao diện của 1 custom content có tên file là **content-[content_key].php**, trong ví dụ trên là **content-product.php**

File giao diện của 1 custom taxonomy có tên file là **taxonomy-[taxonomy_key].php**, trong ví dụ trên là **taxonomy-product-category.php**

Đây là kết quả của đoạn mã ví dụ :

![](https://raw.githubusercontent.com/manhnam91/hmcms/master/docs/images/custom-content-and-custom-taxonomy/1.png)
