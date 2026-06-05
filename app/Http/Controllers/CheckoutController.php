<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Gloudemans\Shoppingcart\Facades\Cart;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function AuthLogin()
    {
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }

    // Dán đoạn code này vào file CheckoutController.php, thay thế cho hàm view_order cũ

    public function view_order($orderId)
    {
        $this->AuthLogin();

        // Lấy thông tin đơn hàng, khách hàng, và vận chuyển
        $order_data = DB::table('tbl_order')
            ->join('tbl_customers', 'tbl_order.customer_id', '=', 'tbl_customers.customer_id')
            ->join('tbl_shipping', 'tbl_order.shipping_id', '=', 'tbl_shipping.shipping_id')
            ->select('tbl_order.*', 'tbl_customers.*', 'tbl_shipping.*')
            // Thêm một alias để tránh xung đột tên cột 'created_at' nếu có
            ->selectRaw('tbl_order.created_at as order_created_at')
            ->where('tbl_order.order_id', $orderId)
            ->first();

        // Lấy chi tiết sản phẩm trong đơn hàng
        $order_details = DB::table('tbl_order_details')
            ->join('tbl_product', 'tbl_order_details.product_id', '=', 'tbl_product.product_id')
            ->select('tbl_order_details.*', 'tbl_product.product_image')
            ->where('tbl_order_details.order_id', $orderId)
            ->get();

        // Đây là cách truyền dữ liệu đúng và đơn giản hơn
        return view('admin.view_order')
            ->with('order', $order_data)
            ->with('order_details', $order_details);
    }

    public function viewOrder($orderId)
    {
        $order = DB::table('tbl_order')
            ->join('tbl_customer', 'tbl_order.customer_id', '=', 'tbl_customer.customer_id')
            ->join('tbl_shipping', 'tbl_order.shipping_id', '=', 'tbl_shipping.shipping_id')
            ->select('tbl_order.*', 'tbl_customer.customer_name', 'tbl_customer.customer_phone', 'tbl_customer.customer_email',
                     'tbl_shipping.shipping_name', 'tbl_shipping.shipping_address', 'tbl_shipping.shipping_phone', 'tbl_shipping.shipping_notes', 'tbl_shipping.shipping_estimated_date')
            ->where('tbl_order.order_id', $orderId)
            ->first();

        $order_details = DB::table('tbl_order_details')
            ->where('order_id', $orderId)
            ->get();

        return view('admin.view_order')->with(compact('order', 'order_details'));
    }

    public function update_order_status($order_id)
    {
        // Lấy đơn hàng
        $order = DB::table('tbl_order')
            ->where('order_id', $order_id)
            ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng');
        }

        // Cập nhật trạng thái đơn hàng
        DB::table('tbl_order')
            ->where('order_id', $order_id)
            ->update(['order_status' => request('order_status')]);

        return redirect()->back()->with('update_success', 'Cập nhật trạng thái thành công!');
    }

    public function add_customer(Request $request)
    {
        // Kiểm tra dữ liệu gửi từ form
        $request->validate([
            'customer_name' => 'required|max:255',
            'customer_email' => 'required|email|max:255|unique:tbl_customers,customer_email',
            'customer_password' => 'required|min:6',
            'customer_phone' => 'required|max:20',
        ], [
            'customer_name.required' => 'Vui lòng nhập họ và tên.',
            'customer_email.required' => 'Vui lòng nhập email.',
            'customer_email.email' => 'Email không đúng định dạng.',
            'customer_email.unique' => 'Email đã được sử dụng.',
            'customer_password.required' => 'Vui lòng nhập mật khẩu.',
            'customer_password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'customer_phone.required' => 'Vui lòng nhập số điện thoại.',
        ]);

        // Lưu dữ liệu vào CSDL
        $data = [
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_password' => md5($request->customer_password), // Hoặc Hash::make() nếu dùng bcrypt
            'customer_phone' => $request->customer_phone,
        ];

        $customer_id = DB::table('tbl_customers')->insertGetId($data);

        Session::put('customer_id', $customer_id);
        Session::put('customer_name', $request->customer_name);

        return Redirect::to('/checkout');
    }

    public function show_checkout()
    {
        $customer_id = Session::get('customer_id');

        // Kiểm tra nếu chưa login
        if (!$customer_id) {
            return Redirect::to('/login-checkout');
        }

        $all_shipping_info = DB::table('tbl_shipping')
            ->where('customer_id', $customer_id)
            ->get();

        return view('pages.checkout.show_checkout')->with('all_shipping_info', $all_shipping_info);
    }

    public function save_checkout_customer(Request $request)
    {
        $data = [];
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_phone'] = $request->shipping_phone;
        $data['shipping_email'] = $request->shipping_email;
        $data['shipping_notes'] = $request->shipping_notes;
        $data['shipping_address'] = $request->shipping_address;

        $shipping_id = $request->input('shipping_id');

        if ($shipping_id) {
            DB::table('tbl_shipping')->where('shipping_id', $shipping_id)->update($data);
            Session::put('shipping_id', $shipping_id);
        } else {
            $shipping_id = DB::table('tbl_shipping')->insertGetId($data);
            Session::put('shipping_id', $shipping_id);
        }

        return Redirect::to('/payment');
    }

    public function payment()
    {
        $cate_product = DB::table('tbl_category_product')
            ->where('category_status', '0')
            ->orderby('category_id', 'desc')
            ->get();

        $shipping_info = null;
        $session_shipping_id = Session::get('shipping_id');

        if ($session_shipping_id) {
            $shipping_info = DB::table('tbl_shipping')
                ->where('shipping_id', $session_shipping_id)
                ->first();
        }

        return view('pages.checkout.payment')
            ->with('category', $cate_product)
            ->with('shipping_info', $shipping_info);
    }

    public function order_place(Request $request)
    {
        // BƯỚC 1: Lấy `shipping_id` từ form người dùng vừa chọn (đây là bước quan trọng nhất)
        $shipping_id = $request->shipping_id;

        // Kiểm tra xem người dùng có chọn địa chỉ không
        if (!$shipping_id) {
            // Nếu không chọn, quay lại trang trước với thông báo lỗi
            return Redirect::back()->with('error', 'Vui lòng chọn một địa chỉ giao hàng.');
        }

        // BƯỚC 2: Lưu `shipping_id` đã chọn vào Session để trang thanh toán sử dụng
        Session::put('shipping_id', $shipping_id);

        // BƯỚC 3: Chuyển hướng người dùng đến trang thanh toán
        return Redirect::to('/payment');
    }

    // ================= THANH TOÁN MOMO =================
    public function momo_payment(Request $request)
    {
        Session::put('order_notes', $request->order_notes); 
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = env('MOMO_PARTNER_CODE');
        $accessKey   = env('MOMO_ACCESS_KEY');
        $secretKey   = env('MOMO_SECRET_KEY');
        $orderInfo   = "Thanh toán qua MoMo";

        // ✅ Đảm bảo lấy đúng số tiền (bỏ dấu phẩy)
        $amount = (int) str_replace(',', '', Cart::total());

        // Tạo mã đơn hàng riêng (có thể lưu vào session)
        $orderId = time() . "";
        Session::put('momo_order_id', $orderId);

        $redirectUrl = url('/momo-return');
        $ipnUrl      = url('/momo-ipn');
        $extraData   = "";

        $requestId   = time() . "";
        $requestType = "captureWallet";

        $rawHash = "accessKey=" . $accessKey .
            "&amount=" . $amount .
            "&extraData=" . $extraData .
            "&ipnUrl=" . $ipnUrl .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&partnerCode=" . $partnerCode .
            "&redirectUrl=" . $redirectUrl .
            "&requestId=" . $requestId .
            "&requestType=" . $requestType;

        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "MoMo Payment",
            "storeId"     => "MomoTestStore",
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId,
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl'      => $ipnUrl,
            'lang'        => 'vi',
            'extraData'   => $extraData,
            'requestType' => $requestType,
            'signature'   => $signature
        ];

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($data))
        ]);
        $result = curl_exec($ch);
        curl_close($ch);

        $jsonResult = json_decode($result, true);

        // ✅ Nếu có link thanh toán thì redirect
        if (isset($jsonResult['payUrl'])) {
            return redirect()->away($jsonResult['payUrl']);
        } else {
            return redirect('/payment')->with('error', 'Không tạo được link MoMo: ' . ($jsonResult['message'] ?? ''));
        }
    }

public function momo_return(Request $request)
{
    // Kiểm tra xem MoMo trả về kết quả thành công không (resultCode = 0)
    if ($request->input('resultCode') == 0) {

        // Lưu thông tin thanh toán vào bảng tbl_payment
        $payment_id = DB::table('tbl_payment')->insertGetId([
            'payment_method' => 'momo',
            'payment_status' => 'Đã thanh toán',
            // ✅ SỬA Ở ĐÂY: Lấy mã giao dịch và thông điệp từ MoMo trả về
            'payment_code'   => $request->input('orderId'),
            'payment_message'=> $request->input('message') ?? 'Successful.', // Nếu message rỗng thì mặc định là 'Successful.'
            'created_at'     => now(),
            'updated_at'     => now()
        ]);

        // Lưu thông tin đơn hàng vào bảng tbl_order
        $order_id = DB::table('tbl_order')->insertGetId([
            'customer_id' => Session::get('customer_id'),
            'shipping_id' => Session::get('shipping_id'),
            'payment_id'  => $payment_id,
            'order_total' => (int) str_replace(',', '', Cart::total()),
            'order_status'=> 1, // Giả sử 1 là "Đang chờ xử lý"
            'order_notes' => Session::get('order_notes'),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Lưu chi tiết đơn hàng vào bảng tbl_order_details
        foreach (Cart::content() as $item) {
            DB::table('tbl_order_details')->insert([
                'order_id' => $order_id,
                'product_id' => $item->id,
                'product_name' => $item->name,
                'product_price' => $item->price,
                'product_sales_quantity' => $item->qty,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Xóa giỏ hàng và các session không cần thiết
        Cart::destroy();
        Session::forget(['shipping_id', 'order_notes']);
        
        // Trả về trang thông báo thành công
        return view('pages.checkout.handcash')->with('message', 'Thanh toán MoMo thành công và đơn hàng đã được tạo!');

    } else {
        // Nếu thanh toán thất bại
        return redirect('/payment')->with('error', 'Thanh toán MoMo thất bại. Vui lòng thử lại.');
    }
}
    // ================= THANH TOÁN MOMO NAPAS =================
public function momo_napas_payment(Request $request)
{
    Session::put('order_notes', $request->order_notes); 
    $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create"; 

    $partnerCode = 'MOMOBKUN20180529';
    $accessKey   = 'klm05TvNBzhg7h7j';
    $secretKey   = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

    $orderInfo   = "Thanh toán qua MoMo Napas test";
    $amount      = (int) \Cart::total(0, '', '');
    $orderId     = time() . "";
    $redirectUrl = url("/momo-napas-return"); // ✅ đổi đúng route return
    $ipnUrl      = url("/momo-ipn");          // webhook (nếu muốn dùng)

    $requestId   = time() . "";
    $requestType = "payWithATM"; // ✅ dùng ATM Napas
    $extraData   = "";

    // Tạo chữ ký
    $rawHash = "accessKey=" . $accessKey .
        "&amount=" . $amount .
        "&extraData=" . $extraData .
        "&ipnUrl=" . $ipnUrl .
        "&orderId=" . $orderId .
        "&orderInfo=" . $orderInfo .
        "&partnerCode=" . $partnerCode .
        "&redirectUrl=" . $redirectUrl .
        "&requestId=" . $requestId .
        "&requestType=" . $requestType;

    $signature = hash_hmac("sha256", $rawHash, $secretKey);

    $data = [
        'partnerCode' => $partnerCode,
        'partnerName' => "Test",
        'storeId'     => "MomoTestStore",
        'requestId'   => $requestId,
        'amount'      => $amount,
        'orderId'     => $orderId,
        'orderInfo'   => $orderInfo,
        'redirectUrl' => $redirectUrl,
        'ipnUrl'      => $ipnUrl,
        'lang'        => 'vi',
        'extraData'   => $extraData,
        'requestType' => $requestType,
        'signature'   => $signature
    ];

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($data))
    ]);
    $result = curl_exec($ch);
    curl_close($ch);

    $jsonResult = json_decode($result, true);

    if (isset($jsonResult['payUrl'])) {
        return redirect()->away($jsonResult['payUrl']);
    } else {
        return redirect('/payment')->with('error', 'MoMo Napas lỗi: ' . json_encode($jsonResult));
    }
}

public function momo_napas_return(Request $request)
{
    $resultCode = $request->input('resultCode');

    if ($resultCode == 0) {
        // Lưu payment
        $payment_id = DB::table('tbl_payment')->insertGetId([
            'payment_method'  => 'momo_napas',
            'payment_status'  => 'Đã thanh toán',
            'payment_code'    => $request->input('orderId'),
            'payment_message' => $request->input('message') ?? 'Thành công',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Lưu order
        $order_id = DB::table('tbl_order')->insertGetId([
            'customer_id' => Session::get('customer_id'),
            'shipping_id' => Session::get('shipping_id'),
            'payment_id'  => $payment_id,
            'order_total' => (int) Cart::total(0, '', ''),
            'order_status'=> 1,
            'order_notes' => Session::get('order_notes'),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Lưu chi tiết giỏ hàng
        foreach (Cart::content() as $item) {
            DB::table('tbl_order_details')->insert([
                'order_id' => $order_id,
                'product_id' => $item->id,
                'product_name' => $item->name,
                'product_price' => $item->price,
                'product_sales_quantity' => $item->qty,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Cart::destroy();
        return view('pages.checkout.handcash')->with('message', 'Thanh toán MoMo Napas thành công!');
    } else {
        // Nếu thất bại cũng lưu log vào payment
        DB::table('tbl_payment')->insert([
            'payment_method'  => 'momo_napas',
            'payment_status'  => 'Thất bại',
            'payment_code'    => $request->input('orderId'),
            'payment_message' => $request->input('message') ?? 'Thanh toán thất bại',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return redirect('/payment')->with('error', 'Thanh toán MoMo Napas thất bại!');
    }
}

    public function complete_order(Request $request)
    {
        $payment_option = $request->payment_option;

        if ($payment_option == 'momo') {
            // ✅ Truyền order_notes sang hàm momo_payment
            return $this->momo_payment($request);

        } elseif ($payment_option == 'atm') {
            // Thanh toán ATM (chưa phát triển)
            return redirect()->back()->with('message', 'Chức năng thanh toán ATM đang phát triển.');

        } elseif ($payment_option == 'cod') {
            // Thanh toán COD
            $payment_id = DB::table('tbl_payment')->insertGetId([
                'payment_method' => 'COD',
                'payment_status' => 'Đang chờ xử lý',
            ]);

            $order_id = DB::table('tbl_order')->insertGetId([
                'customer_id' => Session::get('customer_id'),
                'shipping_id' => Session::get('shipping_id'),
                'payment_id'  => $payment_id,
                'order_total' => (int) Cart::total(0, '', ''),
                'order_status'=> 1,
                'order_notes' => $request->order_notes, // ✅ GHI CHÚ
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            foreach (Cart::content() as $item) {
                DB::table('tbl_order_details')->insert([
                    'order_id' => $order_id,
                    'product_id' => $item->id,
                    'product_name' => $item->name,
                    'product_price' => $item->price,
                    'product_sales_quantity' => $item->qty,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Cart::destroy();
            return view('pages.checkout.handcash')->with('message', 'Đặt hàng thành công, thanh toán khi nhận!');

        } elseif ($payment_option == 'momo_test') {
            // ✅ Truyền order_notes sang hàm momo_napas_payment
            return $this->momo_napas_payment($request);

        } else {
            return redirect('/payment')->with('error', 'Vui lòng chọn phương thức thanh toán hợp lệ!');
        }
    }


    public function logout_checkout()
    {
        Session::flush();
        return Redirect::to('/login-checkout');
    }

    public function login_customer(Request $request)
    {
        $email = $request->email_account;
        $password = md5($request->password_account);

        $result = DB::table('tbl_customers')
            ->where('customer_email', $email)
            ->where('customer_password', $password)
            ->first();

        if ($result) {
            Session::put('customer_id', $result->customer_id);
            Session::put('customer_name', $result->customer_name);

            // ✅ Kiểm tra xem có "url.intended" hay không
            $redirectUrl = Session::pull('url.intended', null);

            if ($redirectUrl) {
                return Redirect::to($redirectUrl);
            }

            // Mặc định sau khi login xong sẽ về checkout
            return Redirect::to('/checkout');
        } else {
            return Redirect::to('/login-checkout')->with('error', 'Sai tài khoản hoặc mật khẩu.');
        }
    }

    public function login_checkout()
    {
    $cate_product = DB::table('tbl_category_product')
        ->where('category_status', '0')
        ->orderby('category_id', 'desc')
        ->get();

    return view('pages.checkout.login_checkout')->with('category', $cate_product);
    }

    // Hiển thị danh sách thanh toán
    public function manage_payment()
    {
        $this->AuthLogin();

        $all_payment = DB::table('tbl_payment')
            ->join('tbl_order', 'tbl_payment.payment_id', '=', 'tbl_order.payment_id')
            ->join('tbl_customers', 'tbl_order.customer_id', '=', 'tbl_customers.customer_id')
            ->select(
                'tbl_payment.payment_id',
                'tbl_payment.payment_method',
                'tbl_payment.payment_status',
                'tbl_payment.payment_code',
                'tbl_payment.payment_message',
                'tbl_payment.created_at',
                'tbl_customers.customer_name',
                'tbl_customers.customer_email',
                'tbl_customers.customer_phone',
                'tbl_order.order_total'
            )
            ->orderBy('tbl_payment.payment_id', 'desc')
            ->paginate(10);

        return view('admin.manage_payment')->with('all_payment', $all_payment);
    }

    // Xem chi tiết 1 giao dịch thanh toán
    public function view_payment($payment_id)
    {
        $this->AuthLogin();

        // Lấy thông tin thanh toán + khách hàng + đơn hàng
        $payment = DB::table('tbl_payment')
            ->join('tbl_order', 'tbl_payment.payment_id', '=', 'tbl_order.payment_id')
            ->join('tbl_customers', 'tbl_order.customer_id', '=', 'tbl_customers.customer_id')
            ->select(
                'tbl_payment.*',
                'tbl_payment.created_at as payment_date', // 👈 hoặc payment_date
                'tbl_order.order_id',
                'tbl_order.order_total',
                'tbl_customers.customer_name',
                'tbl_customers.customer_email',
                'tbl_customers.customer_phone'
            )

            ->where('tbl_payment.payment_id', $payment_id)
            ->first();

        // Lấy danh sách sản phẩm của đơn hàng
        $products = DB::table('tbl_order_details')
            ->where('order_id', $payment->order_id)
            ->get();

        return view('admin.view_payment')
            ->with('payment', $payment)
            ->with('products', $products);
    }

    public function delete_order($order_id)
    {
        $this->AuthLogin();
        DB::table('tbl_order')->where('order_id', $order_id)->delete();
        Session::put('message', 'Xóa đơn hàng thành công!');
        return Redirect::to('manage-order');
    }

    public function add_new_address()
    {
        $cate_product = DB::table('tbl_category_product')
            ->where('category_status', '0')
            ->orderby('category_id', 'desc')
            ->get();

        return view('pages.checkout.add_new_address')->with('category', $cate_product);
    }

    public function save_new_address(Request $request)
    {
        $customer_id = session('customer_id');

        $data = [
            'customer_id'      => $customer_id,
            'shipping_name'    => $request->shipping_name,
            'shipping_address' => $request->shipping_address,
            'shipping_phone'   => $request->shipping_phone,
            'shipping_email'   => $request->shipping_email,
            'shipping_notes'   => $request->shipping_notes ?? null,
            'is_default'       => $request->has('is_default') ? 1 : 0,
            'created_at'       => now(),
            'updated_at'       => now(),
        ];

        try {
            $shipping_id = DB::table('tbl_shipping')->insertGetId($data);
            Session::put('shipping_id', $shipping_id);

            return Redirect::to('/payment')->with('message', 'Thêm địa chỉ mới thành công!');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'Lỗi khi thêm địa chỉ: ' . $e->getMessage());
        }
    }
    // Trong file CheckoutController.php

    public function delete_address($shipping_id)
    {
        // Lấy customer_id đang đăng nhập từ session
        $customer_id = Session::get('customer_id');

        // Tìm địa chỉ cần xóa
        $address = DB::table('tbl_shipping')
            ->where('shipping_id', $shipping_id)
            ->where('customer_id', $customer_id) // Quan trọng: Đảm bảo người dùng chỉ có thể xóa địa chỉ của chính họ
            ->first();

        // Nếu không tìm thấy địa chỉ (có thể do người dùng cố tình thay đổi ID)
        if (!$address) {
            return Redirect::back()->with('error', 'Không tìm thấy địa chỉ hoặc bạn không có quyền xóa địa chỉ này.');
        }
        
        // Kiểm tra xem địa chỉ có phải là địa chỉ mặc định không
        if ($address->is_default) {
            return Redirect::back()->with('error', 'Bạn không thể xóa địa chỉ mặc định. Vui lòng chọn địa chỉ khác làm mặc định trước.');
        }

        // Nếu mọi thứ hợp lệ, tiến hành xóa
        DB::table('tbl_shipping')->where('shipping_id', $shipping_id)->delete();

        // Chuyển hướng về trang trước đó với thông báo thành công
        return Redirect::back()->with('message', 'Đã xóa địa chỉ thành công!');
    }// app/Http/Controllers/CheckoutController.php

    // Phương thức để HIỂN THỊ form chỉnh sửa
    public function edit_address($shipping_id)
    {
        $customer_id = Session::get('customer_id');

        // Lấy thông tin địa chỉ cần sửa, đảm bảo nó thuộc về người dùng đang đăng nhập
        $shipping_info = DB::table('tbl_shipping')
            ->where('customer_id', $customer_id)
            ->where('shipping_id', $shipping_id)
            ->first();

        // Nếu không tìm thấy, quay về
        if (!$shipping_info) {
            return Redirect::to('/checkout')->with('error', 'Không tìm thấy địa chỉ.');
        }

        return view('pages.checkout.edit_address')->with('shipping_info', $shipping_info);
    }

    // Phương thức để XỬ LÝ việc cập nhật
    public function update_address(Request $request, $shipping_id)
    {
        $customer_id = Session::get('customer_id');

        // Dữ liệu mới từ form
        $data = [
            'shipping_name'    => $request->shipping_name,
            'shipping_address' => $request->shipping_address,
            'shipping_phone'   => $request->shipping_phone,
            'shipping_email'   => $request->shipping_email,
            'updated_at'       => now(),
        ];

        // KIỂM TRA QUAN TRỌNG: Nếu người dùng chọn địa chỉ này làm mặc định
        if ($request->has('is_default')) {
            // 1. Bỏ tất cả các địa chỉ khác của người dùng này ra khỏi trạng thái mặc định
            DB::table('tbl_shipping')
                ->where('customer_id', $customer_id)
                ->update(['is_default' => 0]);

            // 2. Gán trạng thái mặc định cho địa chỉ đang được cập nhật
            $data['is_default'] = 1;
        }

        // Cập nhật địa chỉ
        DB::table('tbl_shipping')
            ->where('customer_id', $customer_id)
            ->where('shipping_id', $shipping_id)
            ->update($data);

        return Redirect::to('/checkout')->with('message', 'Cập nhật địa chỉ thành công!');
    }
    public function show_history()
    {
        // Kiểm tra xem khách hàng đã đăng nhập chưa
        $customer_id = Session::get('customer_id');
        if (!$customer_id) {
            return Redirect::to('/login-checkout')->with('error', 'Vui lòng đăng nhập để xem lịch sử đơn hàng.');
        }

        // Lấy tất cả đơn hàng của khách hàng đang đăng nhập
        $orders = DB::table('tbl_order')
            ->where('customer_id', $customer_id)
            ->orderBy('order_id', 'desc') // Sắp xếp đơn hàng mới nhất lên đầu
            ->get();

        // Trả về view và truyền dữ liệu đơn hàng sang
        return view('pages.history.show_history')->with('orders', $orders);
    }

// Thêm phương thức này vào trong class CheckoutController
    public function view_customer_order($order_id)
    {
        // Kiểm tra đăng nhập
        $customer_id = Session::get('customer_id');
        if (!$customer_id) {
            return Redirect::to('/login-checkout')->with('error', 'Vui lòng đăng nhập để xem đơn hàng.');
        }

        // Lấy thông tin đơn hàng, ĐẢM BẢO đơn hàng này thuộc về khách hàng đang đăng nhập
        $order = DB::table('tbl_order')
            ->join('tbl_customers', 'tbl_order.customer_id', '=', 'tbl_customers.customer_id')
            ->join('tbl_shipping', 'tbl_order.shipping_id', '=', 'tbl_shipping.shipping_id')
            ->select('tbl_order.*', 'tbl_customers.*', 'tbl_shipping.*')
            ->where('tbl_order.order_id', $order_id)
            ->where('tbl_order.customer_id', $customer_id) // **Dòng bảo mật quan trọng**
            ->first();

        // Nếu không tìm thấy đơn hàng (do sai id hoặc không phải của khách hàng) -> quay về
        if (!$order) {
            return Redirect::to('/lich-su-don-hang')->with('error', 'Không tìm thấy đơn hàng này.');
        }

        // Lấy chi tiết các sản phẩm trong đơn hàng
        $order_details = DB::table('tbl_order_details')
            ->where('order_id', $order_id)
            ->get();

        return view('pages.history.view_customer_order')
            ->with('order', $order)
            ->with('order_details', $order_details);
    }
    public function manage_order()
{
    $this->AuthLogin();
    $all_order = DB::table('tbl_order')
        ->join('tbl_customers', 'tbl_order.customer_id', '=', 'tbl_customers.customer_id')
        ->select('tbl_order.*', 'tbl_customers.customer_name')
        ->orderBy('tbl_order.order_id', 'desc')
        ->paginate(10);

    return view('admin.manage_order')->with('all_order', $all_order);
}

}
