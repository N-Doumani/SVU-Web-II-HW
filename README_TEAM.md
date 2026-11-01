# دليل الفريق: الطلب 5 (Styling & Responsiveness)

هالمجلد هو **القالب الأساسي للمشروع**. التصميم والتجاوب مع الموبايل خالصين، وكل واحد بيضيف شغله فوقه بدون ما يكتب CSS أو HTML تصميم من عنده.

> القاعدة الذهبية: **لا حدا يعدّل `style.css` أو `ui.js` أو أسماء الـ `id` / `name` بالفورمات.** إذا بدك تغيير بالتصميم احكيني.

---

## 1) محتوى المجلد

```
student_registration_system/
├── index.html             ← الصفحة الرئيسية
├── login.html             ← فورم تسجيل الدخول   (يرسل إلى login.php)
├── register.html          ← فورم إنشاء حساب      (يرسل إلى register.php)
├── add_student.php        ← إضافة طالب
├── view_students.php      ← عرض الطلاب + بحث + ترتيب + صفحات + حذف
├── update_student.php     ← تعديل طالب
├── preview_html/          ← نسخة HTML من كل الصفحات للتفرج بدون XAMPP (احذفوه قبل التسليم)
├── screenshots/           ← صور كل الصفحات (كمبيوتر + موبايل)
├── includes/
│   ├── header.php         ← الـ <head> + القائمة العلوية (Navbar)
│   ├── footer.php         ← الفوتر + سكربتات Bootstrap
│   ├── student_form.php   ← فورم الطالب (مشترك بين الإضافة والتعديل)
│   └── helpers.php        ← دوال عرض: e() · avatar_html() · ...
└── assets/
    ├── css/style.css      ← كل التصميم (Bootstrap 5 + ثيم المشروع)
    ├── js/ui.js           ← سلوك الواجهة فقط (بدون AJAX وبدون قاعدة بيانات)
    └── img/               ← صورة افتراضية للطالب + أيقونة الموقع
```

**للتفرج بسرعة (بدون XAMPP):** افتحوا مجلد `preview_html` ودبل كليك على `index.html`. كل الصفحات موجودة فيه كـ HTML وفيكم تتنقلوا بينها من القائمة.
هي نسخة للمعاينة بس: الفورمات والبحث ما بيحفظوا شي، و**لا تعدّلوا فيها** لأنها نسخة مولّدة من ملفات الـ PHP.
⚠️ **قبل التسليم النهائي والرفع على الاستضافة احذفوا مجلد `preview_html`.**

**التشغيل الحقيقي:** حطوا المجلد جوّا `htdocs` بالـ XAMPP، شغّلوا Apache، وافتحوا `http://localhost/student_registration_system/`.
الصفحات بتشتغل من هلق ببيانات تجريبية (DEMO DATA) لحتى يجهز الـ Backend.

> لازم يكون في إنترنت، لأن Bootstrap والخطوط بيتحمّلوا من النت.

---

## 2) المطلوب من كل واحد

### الطلب 1: قاعدة البيانات (MySQL)
- أسماء الأعمدة لازم تكون **حرفياً** متل الطلب، لأن الصفحات بتقرأ هالأسماء:
  - `students`: `id, name, email, phone, address, profile_picture, created_at`
  - `users`: `id, username, email, password, created_at`
- `phone` = `VARCHAR(15)` (الفورم محدد بـ 15 حرف)، و`created_at` بقيمة افتراضية `CURRENT_TIMESTAMP`.

### الطلب 2: الواجهة (Frontend JS)
- الصفحات جاهزة بالتصميم، **لا تعيدوا كتابتها**. شغلكم هو منطق الـ JavaScript.
- اعملوا ملف `assets/js/main.js` وضيفوه **تحت** سطر `ui.js` (في تعليق جاهز بكل صفحة HTML).
- تطابق كلمة السر بصفحة `register.html`:
  ```js
  UI.setFieldError('#confirm_password', 'Passwords do not match'); // خطأ
  UI.clearFieldError('#confirm_password', true);                   // صح
  ```

### الطلب 3: الـ Backend (PHP)
- بأول كل صفحة PHP في بلوك مكتوب عليه **`BACKEND CODE GOES HERE`**. اكتبوا كودكم هونيك بس.
- ابدؤوا كل صفحة بـ `session_start();` وبعد تسجيل الدخول خزّنوا:
  `$_SESSION['user_id']` و `$_SESSION['username']` (القائمة العلوية بتعرض اسم المستخدم وزر Logout تلقائياً).
- لما تجهز الاستعلامات **احذفوا بلوك `DEMO DATA`**.
- أسماء الحقول اللي بتوصلكم:

| الصفحة | الطريقة | الحقول |
|---|---|---|
| `login.html` → `login.php` | POST | `login` (اسم مستخدم أو إيميل)، `password` |
| `register.html` → `register.php` | POST | `username`، `email`، `password`، `confirm_password` |
| `add_student.php` | POST + ملف | `name`، `email`، `phone`، `address`، `profile_picture` (ملف) |
| `update_student.php` | GET `?id=` ثم POST | نفس حقول الإضافة + `id` (مخفي) |
| حذف (نافذة التأكيد) → `delete_student.php` | **POST** | `id` |

- **الرسائل:** بعد أي عملية اعملوا redirect مع `?msg=` والرسالة بتطلع لحالها بشكل مرتب:

| الكود | وين بتستعملوه |
|---|---|
| `added` · `updated` · `deleted` | بعد الإضافة / التعديل / الحذف ← `view_students.php?msg=added` |
| `invalid` | فشل تسجيل الدخول ← `login.html?msg=invalid` |
| `exists` · `mismatch` | فشل التسجيل ← `register.html?msg=exists` |
| `registered` | نجاح التسجيل ← `login.html?msg=registered` |
| `logout` | بعد الخروج ← `login.html?msg=logout` |
| `login_required` | صفحة محمية بدون تسجيل دخول |
| `not_found` · `upload_error` · `error` | حالات خطأ عامة |

- **أخطاء التحقق من السيرفر:** املؤوا `$errors['email'] = 'This email is already used';` وخلّوا `$student = $_POST;`، والفورم بيعلّم الحقل بالأحمر وبيحافظ على اللي كتبه المستخدم.

### الطلب 4: الميزات المتقدمة
- **رفع الصور:** اسم حقل الملف `profile_picture`. خزّنوا الصورة بمجلد `uploads/` وحطوا المسار (مثلاً `uploads/abc.jpg`) بالعمود `profile_picture`، والصورة بتطلع بالجدول تلقائياً.
- **البحث والصفحات والترتيب:** `view_students.php` بيستقبل `search` و `sort` و `page` من `$_GET`. عبّوا هالمتغيرات قبل `require header.php`:
  ```php
  $students      = [...];   // صفوف الصفحة الحالية (LIMIT 10 OFFSET ...)
  $totalStudents = 42;      // SELECT COUNT(*) بنفس شرط البحث
  $search, $sort, $page, $perPage = 10;
  ```
  قيم `sort`: `newest` · `oldest` · `name_asc` · `name_desc`.
  أزرار Previous / Next وأرقام الصفحات بتتولّد لحالها.
- **AJAX Validation:** اعملوا `assets/js/validation.js` وضيفوه بالصفحة هيك:
  ```php
  $pageScripts = ['assets/js/validation.js'];   // قبل require footer.php
  ```
  الفورم: `#studentForm`، والحقول: `#name` `#email` `#phone` `#address`.
  ```js
  UI.setFieldError('#email', 'Invalid email');   // يعلّم الحقل بالأحمر مع الرسالة
  UI.clearFieldError('#email', true);            // يشيل الخطأ ويعلّمه بالأخضر
  UI.toast('Saved!', 'success');                 // إشعار صغير
  ```

---

## 3) أدوات جاهزة بالـ `ui.js`

| الأداة | الاستعمال |
|---|---|
| `UI.toast(text, type)` | إشعار، والأنواع: `success` · `danger` · `warning` · `info` |
| `UI.alert(text, type)` | رسالة داخل `#pageAlert` |
| `UI.setFieldError(input, msg)` / `UI.clearFieldError(input)` | أخطاء الحقول |
| `UI.setLoading(button, true/false)` | دوّارة تحميل على الزر |

---

## 4) التجاوب مع الموبايل (للتجربة)

- كل التخطيط مبني على Bootstrap Grid: `container` → `row` → `col-12 col-md-6 col-lg-4`.
- القائمة العلوية بتتحوّل لزر ☰ تحت عرض 992px.
- **جدول الطلاب بيتحوّل لبطاقات على الموبايل** (كلاس `.table-stack`، وكل `<td>` عليه `data-label`).
- الفورمات عمودين على الشاشة الكبيرة وعمود واحد على الموبايل، والأزرار بتصير بعرض كامل.
- للتجربة: Chrome ← `F12` ← `Ctrl + Shift + M` وجرّبوا 375px و 768px و 1366px.
