# Website Selling Food

Website Selling Food la do an web ban thuc pham duoc xay dung bang Laravel. He thong gom giao dien khach hang de xem san pham, tim kiem, them gio hang, dat hang va thanh toan; dong thoi co khu vuc quan tri de quan ly danh muc, san pham, don hang, thanh toan va thong ke.

## Cong nghe su dung

- PHP 8.0.2 tro len
- Laravel 9.x
- MySQL/MariaDB
- Composer
- Node.js va NPM
- Vite
- Bootstrap, jQuery
- Package gio hang: `bumbummen99/shoppingcart`
- Tich hop thanh toan thu nghiem MoMo

## Chuc nang chinh

### Khach hang

- Xem danh sach san pham o trang chu.
- Xem san pham theo danh muc.
- Xem chi tiet san pham.
- Tim kiem san pham.
- Xem san pham khuyen mai soc.
- Them san pham vao gio hang theo so luong hoac khoi luong.
- Cap nhat va xoa san pham trong gio hang.
- Dang ky, dang nhap va dang xuat tai khoan khach hang.
- Quan ly dia chi giao hang.
- Dat hang va thanh toan bang COD hoac MoMo.
- Xem lich su don hang va chi tiet don hang.
- Them/xoa san pham yeu thich.

### Quan tri

- Dang nhap khu vuc admin.
- Quan ly danh muc san pham.
- Quan ly san pham, hinh anh, don vi tinh, han su dung va phan tram giam gia.
- Bat/tat trang thai danh muc va san pham.
- Quan ly don hang va cap nhat trang thai don hang.
- Quan ly giao dich thanh toan.
- Xem thong ke don hang, san pham va doanh thu.

## Cau truc thu muc

```text
app/
  Http/Controllers/       Controller xu ly frontend, admin, gio hang, checkout, wishlist, thong ke
  Models/                 Model Laravel
config/                   Cau hinh Laravel
database/
  migrations/             Cac migration tao bang du lieu
  seeders/                Seeder
public/
  backend/                CSS, JS, font, image cho trang admin
  frontend/               CSS, JS, font, image cho giao dien khach hang
  uploads/product/        Anh san pham upload
resources/
  views/                  Blade template
  views/admin/            Giao dien quan tri
  views/pages/            Giao dien khach hang
routes/
  web.php                 Dinh nghia route chinh cua website
storage/                  Log, cache, session, file runtime
```

## Yeu cau truoc khi cai dat

May chay du an can co:

- PHP 8.0.2+ va cac extension Laravel thong dung, dac biet `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`.
- Composer.
- Node.js va NPM.
- MySQL hoac MariaDB.

## Cai dat va chay local

1. Clone du an va di chuyen vao thu muc source:

```bash
git clone <repository-url>
cd websitesellfood
```

2. Cai thu vien PHP:

```bash
composer install
```

3. Cai thu vien frontend:

```bash
npm install
```

4. Tao file cau hinh moi truong:

```bash
cp .env.example .env
php artisan key:generate
```

Neu dung PowerShell tren Windows:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

5. Cau hinh database trong `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=websitesellfood
DB_USERNAME=root
DB_PASSWORD=
```

Tao database trung voi `DB_DATABASE` truoc khi chay migration.

6. Chay migration:

```bash
php artisan migrate
```

7. Chay frontend asset:

```bash
npm run dev
```

8. Mo them terminal khac va chay Laravel:

```bash
php artisan serve
```

Mac dinh website se chay tai:

```text
http://127.0.0.1:8000
```

## Tai khoan admin

Du an co bang `tbl_admin` nhung `DatabaseSeeder` hien chua tao san tai khoan admin. Can tao tai khoan trong database truoc khi dang nhap `/admin`.

Vi du SQL:

```sql
INSERT INTO tbl_admin (admin_email, admin_password, admin_name, admin_phone, created_at, updated_at)
VALUES ('admin@example.com', '123456', 'Admin', '0900000000', NOW(), NOW());
```

Luu y: controller admin hien dang so sanh mat khau dang plain text trong `tbl_admin.admin_password`.

## Cau hinh MoMo

Chuc nang MoMo wallet doc cac bien moi truong sau:

```env
MOMO_PARTNER_CODE=
MOMO_ACCESS_KEY=
MOMO_SECRET_KEY=
```

Khi test thanh toan online tren local, can dam bao MoMo co the redirect ve cac URL:

- `/momo-return`
- `/momo-napas-return`
- `/momo-ipn`

Neu local khong public internet, nen dung cong cu tunnel nhu ngrok de tao URL public.

## Route quan trong

- `/` hoac `/trang-chu`: trang chu.
- `/danh-muc-san-pham/{category_id}`: san pham theo danh muc.
- `/chi-tiet-san-pham/{product_id}`: chi tiet san pham.
- `/show-cart`: gio hang.
- `/login-checkout`: dang nhap/dang ky khach hang.
- `/checkout`: chon/thiet lap dia chi giao hang.
- `/payment`: thanh toan.
- `/lich-su-don-hang`: lich su don hang cua khach hang.
- `/wishlist`: danh sach yeu thich.
- `/admin`: dang nhap admin.
- `/dashboard`: dashboard admin.
- `/all-category-product`: quan ly danh muc.
- `/all-product`: quan ly san pham.
- `/manage-order`: quan ly don hang.
- `/manage-payment`: quan ly thanh toan.
- `/statistics-orders`, `/statistics-products`, `/statistics-revenue`: thong ke.

## CSDL chinh

Cac bang nghiep vu chinh cua du an:

- `tbl_admin`: tai khoan quan tri.
- `tbl_category_product`: danh muc san pham.
- `tbl_product`: san pham.
- `tbl_customers`: khach hang.
- `tbl_shipping`: dia chi giao hang.
- `tbl_order`: don hang.
- `tbl_order_details`: chi tiet don hang.
- `tbl_payment`: giao dich thanh toan.
- `tbl_wishlist`: san pham yeu thich.

## Luu y khi khoi tao CSDL

- File `database/migrations/2025_07_21_084209_tbl_payment.php` tao khoa ngoai toi `tbl_order`, trong khi migration `tbl_order` co timestamp chay sau. Neu `php artisan migrate` bao loi bang `tbl_order` chua ton tai, can dieu chinh thu tu migration hoac tao lai migration payment sau migration order.
- File `database/migrations/2025_08_19_232609_create_tbl_wishlist_table.php` hien khong phai migration tao bang wishlist ma dang chua noi dung model `Product`. Can sua file nay thanh migration dung neu muon tao bang `tbl_wishlist` bang `php artisan migrate`.
- Mot so cot duoc controller su dung nhu `order_notes`, `is_default`, `amount`, `customer_*` trong thanh toan can duoc doi chieu lai voi migration thuc te truoc khi migrate moi hoan toan.

## Lenh huu ich

```bash
# Kiem tra route
php artisan route:list

# Xoa cache cau hinh/view/route
php artisan optimize:clear

# Build asset production
npm run build

# Chay test mac dinh
php artisan test
```

## Docker

Du an co `Dockerfile` cho PHP 8.2 Apache. Tuy nhien Dockerfile dang copy file `./.render/apache.conf`; neu file nay khong ton tai thi build Docker se loi. Can bo sung file cau hinh Apache do hoac sua Dockerfile truoc khi build.

```bash
docker build -t websitesellfood .
docker run --env-file .env -p 10000:10000 websitesellfood
```

## Ghi chu phat trien

- Anh san pham upload duoc luu trong `public/uploads/product`.
- Giao dien admin nam trong `resources/views/admin`.
- Giao dien khach hang nam trong `resources/views/pages`.
- Logic gio hang su dung facade `Cart` cua package shopping cart.
- Du an dang dung session de luu trang thai dang nhap admin/khach hang va thong tin checkout.
