# Hướng dẫn tạo giao diện căn bản

Tất cả các giao diện của HoaMai CMS nằm trong thư mục hm_themes, để bắt đầu tạo giao diện mới, bạn cần tạo 1 thư mục mới tại đây để chứa giao diện của bạn, tên thư mục viết thường và không chứa dấu cách hay các ký tự đặc biệt hay dấu tiếng việt.

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

Theme Name: Tên giao diện của bạn, bạn có thể đặt tên theo ý bạn.
Description: Mô tả giao diện của bạn.
Version: Tên của phiên bản, có thể là chữ hoặc số.
Version Number: Thứ tự của phiên bản, bắt buộc là dạng số.

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
