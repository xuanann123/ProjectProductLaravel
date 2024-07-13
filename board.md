#Checklist Công Việc Phân quyền user
1. Route
2. Controller
3. Views
---- layouts
---- dashboard
#Checklist công việc với module user
-> Đã có database sẵn
I. Hiển thị danh sách sản phẩm
1. Route
2. Tạo ra controller (AdminUserController)
3. Get dữ liệu đổ ra view
II,Chức năng tìm kiếm
1. Lấy được dữ liệu từ form request với phương thức get 
2. Kiểm tra request đó tồn tại không
3. Gán giá trị của keyword 
4.Sử dụng câu điều kiện và lấy ra
5. Kiểm tra xem có bản ghi không có bản ghi nào thì thông báo
-> Tối ưu từ khoá đó bằng các setvalue = reuqest()->input
III,Chức năng thêm user
1. Route
2. Tạo controller
3. Tạo view
4. Validate
5. Lấy dữ liệu
6. Đi thêm dữ liệu
7. Thông báo cho người dùng
IV, Xoá user ra khỏi hệ thống
1.Tạo url ở view
2.Nhận giá trị id ở route 
3.Đẩy qua controller rồi xoá
4.Thông báo sang view
V,Phần tác vụ trên bản ghi
1. Kíck hoạt softdelete
2. Đặt Url vào các bản ghi muốn xoá ở phần view -> xoá mền
3. Kích hoạt bản ghi bằng cách reqest()->fullUrlWithQuery([]): Nối chuỗi url với tham số truyền vào là gì
4. Thao tác trên controller lấy ra danh sách trong thùng rác và lấy danh sách hiển thị chưa bị xoá
VI, Phần thực thi nhièu tác vụ trên bản ghi
1. Lấy danh sách các bản ghi cần thao tác
2. Đặt name = nameInputp[] -> sẽ lấy được danh sách bản ghi
3. Kiểm tra xem thằng id nó có trùng với lại của chính không
4. Kiểm nếu trùng thì loại bỏ nó ra khỏi mảng còn nếu không thì bắt đầu đi kiểm tra
5. Kiểm tra xem còn các phần khác không nếu còn thì kiểm phần act nó bắt thực thi cái gì
6. Thông báo cho người dùng biết các nhiệm đã thực thi hoặc thực thi lỗi


#TẠO CÁC BẢNG TRONG PHẦN PHÂN QUYỀN ROLE PERMISSION VÀ USER 
Mối quan hệ giữa các bảng per và role là nn -> tạo ra một bảng mới là role_permission
##Permission 
- id
- name
- slug(post.add) -> Chuỗi này nó ở module nào và có quyền gì
- description => Có thể để trống vì phần name đã gánh
##Role
- id
- name
- description -> bắt buộc -> vì desc mô tả cho cái name
##Role_Permission
- id 
- role_id
- permission_id
##user_role
- id 
- user_id
- role_id


#CHECK LIST LÀM VIỆC VỚI PERMISSION
*ADD
0. Add fillable vào DB
1. Tạo controller
2. Tạo một route
3. Tạo view
4. Ghép giao diện
5. Validate
6.  Thêm dữ liệu
7. Thông báo cho người dùng
8. Show dữ liệu quyền cho người dùng
*Show
1. Lấy được danh sách quyền ở controller(group by)
2. Gửi data qua view -> duyệt qua vòng lập rồi lấy theo danh mục
3. Xuất ra view theo cấu trúc nhóm
*Edit
--Tạo ra một đường dẫn link ở view
1. Tạo function controller
2. Tạo route -controller lấy id xuống và thao tác
3. Tạo view -> coppy nút sửa xoá
4. validate
5. update 
6. thông báo
4. Lấy được bản ghi cần chỉnh sửa
*Delete
--Xoá quyền đó đi
1. Tạo ra một controller
2. Tạo route
3. Tạo view -> controller
4. Lấy được phần id cần xoá
4.5 confirm có xoá hay không
5. Xoá id đó
6. Thông báo cho người dùng



#CHECK LIST LÀM VIỆC VỚI ROLE
*LIST
0. Bước đầu tạo ra controller
1. Tạo route -> tạo controller index()
2. Tạo view
3. Lấy dữ liệu trên DB
4. Đẩy về giao diện người dùng
*ADD
0. Add fillable vào DB
1. Tạo route
2. Tạo một controller
--Lấy toàn bộ danh sách permissions ở phần giao diện ra
3. Tạo view
4. Ghép giao diện
5. Validate
6. Thêm dữ liệu cả vào role lẫn cả vào role_permisstion
7. Thông báo cho người dùng
8. Show dữ liệu quyền cho người dùng
*Show
1. Lấy được danh sách quyền ở controller(group by)
2. Gửi data qua view -> duyệt qua vòng lập rồi lấy theo danh mục
3. Xuất ra view theo cấu trúc nhóm
*Edit
--Tạo ra một đường dẫn link ở view
1. Route
--- edit
--- update
2. Controller
--- edit -> load dữ liệu từ db đổ vào
--- update -> Lấy toàn bộ dữ liệu được update -> update()
3. Add link vào phần danh sách
4. Tạo view
5.Gửi dữ liệu qua view 
-permissions
-role
7. Update
-role
-role_permission
8. Chuỷen hướng 
9. Thông báo

*Delete
--Xoá quyền đó đi
1. Tạo ra một controller
2. Tạo route
3. Tạo view -> controller
4. Lấy được phần id cần xoá
4.5 confirm có xoá hay không
5. Xoá id đó
6. Thông báo cho người dùng

*Quyền user đăng nhập
-hasPermission() : Kiểm tra quyền đăng nhập

*Phân quyền với Gate(define+check)
1. Cách làm việc với gate
-Định nghĩa
-Kiểm tra quyền
2. Tư duy áp dụng Gate trong hệ thống phân quyền
3. Định nghĩa Gate bằng vòng lặp
4. Test kiểm tra quyền tại controller
5. Kiểm tra quyền tại route
6. Kiểm tra quyền Blade Sidebar Menu

#NHỮNG VIỆC CẦN PHẢI LÀM Ở PHẦN NÀY
1. Tạo ra danh sách quyền của từng module
2. Tạo rằng buộc quyền trên các route -> can()
3. Tạo rằng buộc hiển thị trên view -> sidebar menu
4. Tạo rằng buộc hiển thị trên các button(edit, delete)
5. Test trên nhiều thành viên với những quyền khác nhau 
MODULE PRODUCT 
Bảng này có mối quan hệ nhiều nhiều với categories products -> bảng trung gian là category_product
1. Tạo bảng products
2. Show list Product
3. Thêm sản phẩm product sẽ như nào
-- Chúng ta tạo route 
-- Controller
-- Views
-- Lấy dữ liệu
-- Validate
-- Thêm vào

*Làm nút tiềm kiếm
-Lấy được dữ liệu từ form -> kiểm tra bên controller rồi từ controller đó tạo câu điều kiện để lấy ra
*Làm phần sủa sản phẩm
1. Tạo controller route 
2. Tạo view
3. Đổ dữ liệu ra view
4. Validate dữ liệu lấy được
5. Đi update vào sản phẩm
*Làm phần xoá sản phẩm
1. Tạo ra một phần xoá mền soft_delete cho table product
2. Thử xoá một dữ liệu


//MODULE POST 
1. Làm phần cat trước(Thêm sửa xoá)

2. Tạo migrate  
3. Liên kết bảng qua mối quan hệ nhiều nhiều (post -> cat)
4. Lấy dữ liệu làm phần view trước
Làm việc với Module order
Tạo order 
Tạo customer 
-> thao tác và lấy dữ liệu






