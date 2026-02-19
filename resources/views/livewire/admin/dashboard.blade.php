
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">لوحة القيادة</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                تسجيل الخروج
                            </button>
                        </form>
                    </li>
                @endauth
                @guest
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">تسجيل الدخول</a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<style>
    body {
        direction: rtl;
        text-align: right;
        background-color: #f8f9fa;
    }

    .sidebar {
        min-height: 100vh;
        background: #1f2937;
        color: #fff;
        padding: 20px;
    }

    .sidebar a {
        color: #fff;
        display: block;
        padding: 10px;
        margin-bottom: 8px;
        border-radius: 8px;
        text-decoration: none;
        transition: 0.3s;
    }

    .sidebar a:hover {
        background: #374151;
    }

    .dashboard-card {
        border-radius: 12px;
        padding: 20px;
        background: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        text-align: center;
        transition: 0.3s;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
    }

</style>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-3 sidebar">

            <h4 class="mb-4">لوحة التحكم</h4>

            <a href="{{ route('admin.dashboard') }}">
                الرئيسية
            </a>

            <a href="{{ route('admin.categories.index') }}">
                إدارة الأقسام
            </a>

            <a href="{{ route('admin.categories.create') }}">
                إنشاء قسم جديد
            </a>

            <a href="{{ route('admin.products.index') }}">
                إدارة المنتجات
            </a>

            <a href="{{ route('admin.products.create') }}">
                إنشاء منتج جديد
            </a>

            <a href="{{ route('admin.orders.index') }}">
                إدارة الطلبات
            </a>

            <hr>

            <a href="{{ route('frontend.products') }}" target="_blank">
                عرض المتجر
            </a>

        </div>


        <!-- Main Content -->
        <div class="col-md-9 p-4">

            <h3 class="mb-4">مرحبًا بك في لوحة الإدارة 👋</h3>

            <div class="row g-4">

                <div class="col-md-4">
                    <div class="dashboard-card">
                        <h5>الأقسام</h5>
                        <p>إدارة جميع أقسام المتجر</p>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-dark btn-sm">
                            عرض الأقسام
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="dashboard-card">
                        <h5>المنتجات</h5>
                        <p>إدارة المنتجات وإضافة جديد</p>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-dark btn-sm">
                            عرض المنتجات
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="dashboard-card">
                        <h5>الطلبات</h5>
                        <p>متابعة طلبات العملاء</p>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-dark btn-sm">
                            عرض الطلبات
                        </a>
                    </div>
                </div>

            </div>

            <hr class="my-5">

            <div class="alert alert-info">
                يمكنك استخدام القائمة الجانبية للتنقل بين أقسام لوحة التحكم بسهولة.
            </div>

        </div>

    </div>
</div>


