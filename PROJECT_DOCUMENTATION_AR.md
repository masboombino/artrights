# توثيق شامل لمشروع ArtRights

## نظرة عامة على المشروع

**ArtRights** هو نظام إدارة حقوق الملكية الفنية الرقمية مبني على إطار عمل Laravel. يهدف المشروع إلى إدارة حقوق الفنانين في أعمالهم الفنية (موسيقى، صور، فيديو، إلخ) من خلال نظام متكامل يشمل:

1. **إدارة الفنانين (Artists)**: تسجيل الفنانين واعتمادهم
2. **إدارة الأعمال الفنية (Artworks)**: رفع الأعمال الفنية واعتمادها
3. **نظام PV (Process-Verbal)**: تسجيل المخالفات المتعلقة باستخدام الأعمال الفنية في المحلات التجارية
4. **نظام المحافظ المالية (Wallets)**: إدارة الأموال للفنانين والوكالات
5. **نظام الشكاوى (Complaints)**: إدارة الشكاوى والتقارير بين المستخدمين
6. **نظام المهام (Missions)**: توزيع المهام على الوكلاء
7. **نظام الإشعارات (Notifications)**: إشعارات داخل التطبيق

---

## البنية التقنية

### التقنيات المستخدمة

- **Laravel Framework**: إطار عمل PHP
- **Spatie Laravel Permission**: إدارة الأدوار والصلاحيات
- **Laravel Sanctum**: المصادقة للواجهات البرمجية (API)
- **MySQL Database**: قاعدة البيانات
- **Eloquent ORM**: للتعامل مع قاعدة البيانات

### هيكل المشروع

```
ArtRights/
├── app/
│   ├── Console/          # أوامر Artisan
│   ├── Events/           # الأحداث
│   ├── Exceptions/       # معالجة الأخطاء
│   ├── Http/
│   │   ├── Controllers/  # المتحكمات (Controllers)
│   │   ├── Middleware/   # الوسطاء (Middleware)
│   │   └── Requests/     # طلبات التحقق
│   ├── Mail/             # رسائل البريد الإلكتروني
│   ├── Models/           # النماذج (Models)
│   ├── Providers/       # مقدمي الخدمات
│   ├── Services/         # الخدمات المخصصة
│   └── View/             # مكونات العرض
├── database/
│   ├── migrations/       # هجرات قاعدة البيانات
│   └── seeders/          # بذور البيانات
├── resources/
│   ├── views/            # قوالب Blade
│   └── js/               # ملفات JavaScript
├── routes/
│   ├── web.php           # مسارات الويب
│   ├── api.php           # مسارات API
│   └── auth.php          # مسارات المصادقة
└── config/               # ملفات الإعدادات
```

---

## الأدوار (Roles) والصلاحيات (Permissions)

### الأدوار في النظام

#### 1. Super Admin (المدير العام)
**الوصف**: أعلى مستوى من الصلاحيات في النظام. يمكنه إدارة كل شيء.

**الصلاحيات**:
- `manage admins`: إدارة المديرين
- `manage gestionnaires`: إدارة المدراء التنفيذيين
- `manage categories`: إدارة الفئات
- `manage agencies`: إدارة الوكالات
- `manage pvs`: إدارة سجلات PV
- `view dashboard`: عرض لوحة التحكم
- `view notifications`: عرض الإشعارات

**الوظائف**:
- إنشاء وإدارة الوكالات
- تعيين المديرين (Admins) والمدراء التنفيذيين (Gestionnaires)
- إدارة الفئات (Categories) للأعمال الفنية
- عرض وإدارة جميع سجلات PV في النظام
- إدارة جميع المستخدمين

#### 2. Admin (المدير)
**الوصف**: يدير وكالة واحدة. مسؤول عن اعتماد الفنانين وإدارة الشكاوى.

**الصلاحيات**:
- `approve artists`: اعتماد الفنانين
- `reject artists`: رفض الفنانين
- `manage users`: إدارة المستخدمين
- `manage complaints`: إدارة الشكاوى
- `manage gestionnaires (category)`: إدارة المدراء التنفيذيين (حسب الفئة)
- `view dashboard`: عرض لوحة التحكم
- `view notifications`: عرض الإشعارات

**الوظائف**:
- اعتماد أو رفض طلبات تسجيل الفنانين الجدد
- إدارة المستخدمين في وكالته
- الرد على الشكاوى الموجهة إليه من الفنانين
- إدارة طلبات إعادة شحن المحافظ
- عرض إحصائيات الوكالة

#### 3. Gestionnaire (المدير التنفيذي)
**الوصف**: يدير العمليات اليومية للوكالة. مسؤول عن اعتماد الأعمال الفنية وإدارة PVs.

**الصلاحيات**:
- `approve artworks`: اعتماد الأعمال الفنية
- `reject artworks`: رفض الأعمال الفنية
- `manage artworks`: إدارة الأعمال الفنية
- `send agents`: إرسال الوكلاء (إنشاء مهام)
- `manage pvs`: إدارة سجلات PV
- `manage agencies`: إدارة الوكالات
- `manage revenues`: إدارة الإيرادات
- `view dashboard`: عرض لوحة التحكم
- `view notifications`: عرض الإشعارات

**الوظائف**:
- اعتماد أو رفض الأعمال الفنية المرفوعة من الفنانين
- إنشاء مهام (Missions) وتوزيعها على الوكلاء
- إدارة سجلات PV: التحقق من الدفعات وإطلاق الأموال للفنانين
- إدارة محفظة الوكالة المالية
- اعتماد طلبات إعادة شحن محافظ الفنانين
- الرد على الشكاوى والتقارير الموجهة إليه

#### 4. Agent (الوكيل)
**الوصف**: يقوم بزيارات ميدانية للمحلات التجارية لتسجيل استخدام الأعمال الفنية.

**الصلاحيات**:
- `create pv`: إنشاء سجلات PV
- `view pvs`: عرض سجلات PV
- `close pv`: إغلاق سجلات PV
- `view law`: عرض القوانين
- `manage payments`: إدارة المدفوعات
- `view dashboard`: عرض لوحة التحكم
- `view notifications`: عرض الإشعارات

**الوظائف**:
- استلام المهام (Missions) من Gestionnaire
- إنشاء سجلات PV جديدة للمحلات التجارية
- إضافة الأجهزة (Devices) المستخدمة في المحل
- إضافة الأعمال الفنية المستخدمة مع حساب الغرامات
- رفع صور كدليل على المخالفة
- إغلاق سجل PV بعد اكتمال التفتيش
- تأكيد استلام الدفعة من صاحب المحل
- إرسال شكاوى وتقارير

#### 5. Artist (الفنان)
**الوصف**: يرفع أعماله الفنية ويدير محفظته المالية.

**الصلاحيات**:
- `view profile`: عرض الملف الشخصي
- `edit profile`: تعديل الملف الشخصي
- `create artwork`: إنشاء عمل فني
- `edit artwork`: تعديل عمل فني
- `delete artwork`: حذف عمل فني
- `view wallet`: عرض المحفظة
- `view related pvs`: عرض سجلات PV المتعلقة بأعماله
- `submit complaint`: إرسال شكوى
- `view dashboard`: عرض لوحة التحكم
- `view notifications`: عرض الإشعارات

**الوظائف**:
- تسجيل حساب جديد (يحتاج اعتماد من Admin)
- رفع الأعمال الفنية (صور، موسيقى، فيديو)
- دفع ضريبة المنصة (Platform Tax) لتفعيل العمل الفني
- إدارة المحفظة المالية: طلب إعادة شحن، عرض المعاملات
- عرض سجلات PV التي استخدمت فيها أعماله
- إرسال شكاوى إلى Admin أو Gestionnaire

---

## النماذج (Models) والعلاقات

### 1. User (المستخدم)
**الجدول**: `users`

**الحقول الرئيسية**:
- `id`: المعرف
- `name`: الاسم الكامل
- `email`: البريد الإلكتروني
- `phone`: رقم الهاتف
- `password`: كلمة المرور (مشفرة)
- `profile_photo_path`: مسار صورة الملف الشخصي
- `agency_id`: معرف الوكالة
- `email_verified_at`: تاريخ التحقق من البريد

**العلاقات**:
- `artist()`: علاقة مع Artist (واحد لواحد)
- `agent()`: علاقة مع Agent (واحد لواحد)
- `notifications()`: علاقة مع Notification (واحد لكثير)
- `agency()`: علاقة مع Agency (كثير لواحد)

**الملاحظات**:
- يستخدم `HasApiTokens` للمصادقة عبر API
- يستخدم `HasRoles` من Spatie لإدارة الأدوار
- يستخدم `SoftDeletes` للحذف الناعم
- يحتوي على accessor `profile_photo_url` الذي يولد URL ديناميكي للصور

### 2. Artist (الفنان)
**الجدول**: `artists`

**الحقول الرئيسية**:
- `id`: المعرف
- `user_id`: معرف المستخدم
- `agency_id`: معرف الوكالة
- `stage_name`: الاسم الفني
- `birth_date`: تاريخ الميلاد
- `birth_place`: مكان الميلاد
- `address`: العنوان
- `identity_document`: مسار وثيقة الهوية
- `status`: الحالة (PENDING_VALIDATION, APPROVED, REJECTED)

**العلاقات**:
- `user()`: علاقة مع User (كثير لواحد)
- `agency()`: علاقة مع Agency (كثير لواحد)
- `artworks()`: علاقة مع Artwork (واحد لكثير)
- `wallet()`: علاقة مع Wallet (واحد لواحد)
- `transactions()`: علاقة مع Transaction (واحد لكثير)
- `complaints()`: علاقة مع Complain (واحد لكثير)

### 3. Artwork (العمل الفني)
**الجدول**: `artworks`

**الحقول الرئيسية**:
- `id`: المعرف
- `artist_id`: معرف الفنان
- `category_id`: معرف الفئة
- `title`: العنوان
- `description`: الوصف
- `file_path`: مسار الملف
- `status`: الحالة (PENDING, APPROVED, REJECTED)
- `rejection_reason`: سبب الرفض
- `platform_tax_status`: حالة ضريبة المنصة (PENDING, PAID)
- `platform_tax_amount`: مبلغ ضريبة المنصة
- `platform_tax_paid_at`: تاريخ دفع الضريبة

**العلاقات**:
- `artist()`: علاقة مع Artist (كثير لواحد)
- `category()`: علاقة مع Category (كثير لواحد)
- `artworkUsages()`: علاقة مع PVArtwork (واحد لكثير)

**الملاحظات**:
- يجب أن يكون العمل الفني `APPROVED` و `platform_tax_status = PAID` ليستخدم في PV
- يتم حساب `platform_tax_amount` تلقائياً بناءً على الفئة

### 4. PV (Process-Verbal / محضر المخالفة)
**الجدول**: `pv`

**الحقول الرئيسية**:
- `id`: المعرف
- `agent_id`: معرف الوكيل
- `agency_id`: معرف الوكالة
- `mission_id`: معرف المهمة (اختياري)
- `shop_name`: اسم المحل
- `shop_type`: نوع المحل
- `date_of_inspection`: تاريخ التفتيش
- `status`: الحالة (OPEN, CLOSED, FINALIZED)
- `payment_status`: حالة الدفع (PENDING, VALIDATED)
- `payment_method`: طريقة الدفع (CASH, CHEQUE)
- `agent_payment_confirmed`: تأكيد الوكيل للدفع
- `agent_confirmed_at`: تاريخ تأكيد الوكيل
- `cash_received_amount`: المبلغ المستلم نقداً
- `total_amount`: المبلغ الإجمالي
- `base_rate`: السعر الأساسي
- `file_path`: مسار ملفات الأدلة (JSON)
- `payment_proof_path`: مسار إثبات الدفع
- `notes`: ملاحظات
- `closed_at`: تاريخ الإغلاق
- `funds_released_at`: تاريخ إطلاق الأموال
- `finalized_at`: تاريخ الإنهاء

**العلاقات**:
- `agent()`: علاقة مع Agent (كثير لواحد)
- `agency()`: علاقة مع Agency (كثير لواحد)
- `mission()`: علاقة مع Mission (كثير لواحد)
- `devices()`: علاقة مع Device (واحد لكثير)
- `artworkUsages()`: علاقة مع PVArtwork (واحد لكثير)

**الطرق المهمة**:
- `recalculateTotals()`: إعادة حساب المبلغ الإجمالي
- `artistTotals()`: حساب المبالغ لكل فنان
- `canBeFinalized()`: التحقق من إمكانية إنهاء PV
- `canReleaseFunds()`: التحقق من إمكانية إطلاق الأموال
- `markFundsReleased()`: تحديد تاريخ إطلاق الأموال
- `isFinalized()`: التحقق من إنهاء PV
- `evidenceFiles()`: استرجاع قائمة ملفات الأدلة

### 5. PVArtwork (استخدام عمل فني في PV)
**الجدول**: `pv_artwork`

**الحقول الرئيسية**:
- `id`: المعرف
- `pv_id`: معرف PV
- `artwork_id`: معرف العمل الفني
- `device_id`: معرف الجهاز (اختياري)
- `hours_used`: عدد الساعات المستخدمة
- `plays_count`: عدد مرات التشغيل (لم يعد مستخدماً)
- `base_rate`: السعر الأساسي
- `fine_amount`: مبلغ الغرامة المحسوب
- `notes`: ملاحظات

**العلاقات**:
- `pv()`: علاقة مع PV (كثير لواحد)
- `artwork()`: علاقة مع Artwork (كثير لواحد)
- `device()`: علاقة مع Device (كثير لواحد)

**حساب الغرامة**:
```
Fine = (Category Coefficient) × (Device Coefficient) × (Hours/Count) × Base Rate
```

### 6. Device (الجهاز)
**الجدول**: `devices`

**الحقول الرئيسية**:
- `id`: المعرف
- `pv_id`: معرف PV
- `device_type_id`: معرف نوع الجهاز (اختياري)
- `name`: اسم الجهاز
- `type`: نوع الجهاز
- `coefficient`: معامل الجهاز
- `quantity`: الكمية
- `amount`: المبلغ الإجمالي للجهاز
- `notes`: ملاحظات

**العلاقات**:
- `pv()`: علاقة مع PV (كثير لواحد)
- `deviceType()`: علاقة مع DeviceType (كثير لواحد)
- `usages()`: علاقة مع PVArtwork (واحد لكثير)

### 7. Category (الفئة)
**الجدول**: `categories`

**الحقول الرئيسية**:
- `id`: المعرف
- `name`: الاسم
- `description`: الوصف
- `coefficient`: المعامل (يستخدم في حساب الغرامات)
- `exploitation_rate`: معدل الاستغلال

### 8. DeviceType (نوع الجهاز)
**الجدول**: `device_types`

**الحقول الرئيسية**:
- `id`: المعرف
- `name`: الاسم
- `type`: النوع (PUBLIC, COMMERCIAL, PERSONAL)
- `coefficient`: المعامل
- `description`: الوصف

### 9. Agency (الوكالة)
**الجدول**: `agencies`

**الحقول الرئيسية**:
- `id`: المعرف
- `admin_id`: معرف المدير
- `agency_name`: اسم الوكالة
- `wilaya`: الولاية

**العلاقات**:
- `admin()`: علاقة مع User (كثير لواحد)
- `agents()`: علاقة مع Agent (واحد لكثير)
- `artists()`: علاقة مع Artist (واحد لكثير)
- `gestionnaires()`: علاقة مع User (واحد لكثير)
- `pvs()`: علاقة مع PV (واحد لكثير)
- `wallet()`: علاقة مع AgencyWallet (واحد لواحد)

### 10. Agent (الوكيل)
**الجدول**: `agents`

**الحقول الرئيسية**:
- `id`: المعرف
- `user_id`: معرف المستخدم
- `agency_id`: معرف الوكالة
- `badge_number`: رقم الشارة

**العلاقات**:
- `user()`: علاقة مع User (كثير لواحد)
- `agency()`: علاقة مع Agency (كثير لواحد)
- `pvs()`: علاقة مع PV (واحد لكثير)
- `missions()`: علاقة مع Mission (واحد لكثير)

### 11. Mission (المهمة)
**الجدول**: `missions`

**الحقول الرئيسية**:
- `id`: المعرف
- `gestionnaire_id`: معرف المدير التنفيذي
- `agent_id`: معرف الوكيل
- `complaint_id`: معرف الشكوى (اختياري)
- `agency_id`: معرف الوكالة
- `title`: العنوان
- `description`: الوصف
- `location_text`: نص الموقع
- `map_link`: رابط الخريطة
- `latitude`: خط العرض
- `longitude`: خط الطول
- `scheduled_at`: التاريخ المحدد
- `status`: الحالة (ASSIGNED, IN_PROGRESS, DONE, CANCELLED)

**العلاقات**:
- `gestionnaire()`: علاقة مع User (كثير لواحد)
- `agent()`: علاقة مع Agent (كثير لواحد)
- `complaint()`: علاقة مع Complain (كثير لواحد)
- `agency()`: علاقة مع Agency (كثير لواحد)
- `pv()`: علاقة مع PV (واحد لواحد)

### 12. Wallet (محفظة الفنان)
**الجدول**: `wallets`

**الحقول الرئيسية**:
- `id`: المعرف
- `artist_id`: معرف الفنان
- `balance`: الرصيد
- `last_transaction`: آخر معاملة

**العلاقات**:
- `artist()`: علاقة مع Artist (واحد لواحد)

### 13. AgencyWallet (محفظة الوكالة)
**الجدول**: `agency_wallets`

**الحقول الرئيسية**:
- `id`: المعرف
- `agency_id`: معرف الوكالة
- `balance`: الرصيد
- `last_transaction`: آخر معاملة

**العلاقات**:
- `agency()`: علاقة مع Agency (واحد لواحد)
- `transactions()`: علاقة مع AgencyWalletTransaction (واحد لكثير)

### 14. Transaction (المعاملة المالية)
**الجدول**: `transactions`

**الحقول الرئيسية**:
- `id`: المعرف
- `artist_id`: معرف الفنان
- `pv_id`: معرف PV (اختياري)
- `artwork_id`: معرف العمل الفني (اختياري)
- `type`: النوع (PV_PAYMENT, PLATFORM_TAX, WALLET_RECHARGE)
- `amount`: المبلغ
- `payment_method`: طريقة الدفع
- `payment_status`: حالة الدفع
- `description`: الوصف

**العلاقات**:
- `artist()`: علاقة مع Artist (كثير لواحد)
- `pv()`: علاقة مع PV (كثير لواحد)
- `artwork()`: علاقة مع Artwork (كثير لواحد)

### 15. AgencyWalletTransaction (معاملة محفظة الوكالة)
**الجدول**: `agency_wallet_transactions`

**الحقول الرئيسية**:
- `id`: المعرف
- `agency_wallet_id`: معرف محفظة الوكالة
- `pv_id`: معرف PV (اختياري)
- `direction`: الاتجاه (IN, OUT)
- `amount`: المبلغ
- `description`: الوصف

**العلاقات**:
- `agencyWallet()`: علاقة مع AgencyWallet (كثير لواحد)
- `pv()`: علاقة مع PV (كثير لواحد)

### 16. WalletRechargeRequest (طلب إعادة شحن المحفظة)
**الجدول**: `wallet_recharge_requests`

**الحقول الرئيسية**:
- `id`: المعرف
- `artist_id`: معرف الفنان
- `amount`: المبلغ
- `payment_method`: طريقة الدفع (CHEQUE, POSTAL_TRANSFER)
- `transaction_reference`: رقم المرجع
- `bank_name`: اسم البنك
- `account_number`: رقم الحساب
- `card_number`: رقم البطاقة
- `payment_proof_path`: مسار إثبات الدفع
- `notes`: ملاحظات
- `status`: الحالة (PENDING, APPROVED, REJECTED)
- `approved_by`: معرف الموافق
- `rejection_reason`: سبب الرفض
- `approved_at`: تاريخ الموافقة

**العلاقات**:
- `artist()`: علاقة مع Artist (كثير لواحد)
- `approver()`: علاقة مع User (كثير لواحد)

**الطرق**:
- `canBeApproved()`: التحقق من إمكانية الموافقة
- `canBeRejected()`: التحقق من إمكانية الرفض

### 17. Complain (الشكوى/التقرير)
**الجدول**: `complaints`

**الحقول الرئيسية**:
- `id`: المعرف
- `type`: النوع (COMPLAINT, REPORT)
- `complaint_type`: نوع الشكوى المحدد
- `artist_id`: معرف الفنان (اختياري)
- `agent_id`: معرف الوكيل (اختياري)
- `agency_id`: معرف الوكالة
- `sender_user_id`: معرف المرسل
- `sender_role`: دور المرسل
- `target_role`: دور المستهدف
- `target_user_id`: معرف المستهدف
- `admin_id`: معرف المدير (اختياري)
- `gestionnaire_id`: معرف المدير التنفيذي (اختياري)
- `subject`: الموضوع
- `message`: الرسالة
- `images`: الصور (JSON)
- `location_link`: رابط الموقع
- `status`: الحالة (PENDING, RESOLVED)
- `admin_response`: رد المدير
- `admin_response_images`: صور رد المدير (JSON)
- `gestionnaire_response`: رد المدير التنفيذي
- `gestionnaire_response_images`: صور رد المدير التنفيذي (JSON)
- `responded_at`: تاريخ الرد

**العلاقات**:
- `artist()`: علاقة مع Artist (كثير لواحد)
- `agent()`: علاقة مع Agent (كثير لواحد)
- `agency()`: علاقة مع Agency (كثير لواحد)
- `sender()`: علاقة مع User (كثير لواحد)
- `targetUser()`: علاقة مع User (كثير لواحد)
- `admin()`: علاقة مع User (كثير لواحد)
- `gestionnaire()`: علاقة مع User (كثير لواحد)

**الثوابت**:
- `TYPE_COMPLAINT`: شكوى
- `TYPE_REPORT`: تقرير
- أنواع الشكاوى المختلفة (ARTIST_TO_ADMIN, AGENT_TO_GESTIONNAIRE, إلخ)

**الطرق**:
- `resolveType()`: تحديد نوع الشكوى بناءً على المرسل والمستهدف

### 18. Notification (الإشعار)
**الجدول**: `notifications`

**الحقول الرئيسية**:
- `id`: المعرف
- `user_id`: معرف المستخدم
- `type`: النوع
- `sender_id`: معرف المرسل
- `sender_type`: نوع المرسل
- `title`: العنوان
- `message`: الرسالة
- `data`: بيانات إضافية (JSON)
- `is_read`: تم القراءة
- `read_at`: تاريخ القراءة

**العلاقات**:
- `user()`: علاقة مع User (كثير لواحد)
- `sender()`: علاقة مع User (كثير لواحد)

### 19. Law (القانون)
**الجدول**: `laws`

**الحقول الرئيسية**:
- `id`: المعرف
- `language`: اللغة (english, arabic, french)
- `title`: العنوان
- `notice`: الإشعار
- `sections`: الأقسام (JSON)

### 20. ShopType (نوع المحل)
**الجدول**: `shop_types`

**الحقول الرئيسية**:
- `id`: المعرف
- `name`: الاسم
- `category`: الفئة
- `description`: الوصف
- `is_active`: نشط

### 21. FooterSetting (إعدادات التذييل)
**الجدول**: `footer_settings`

**الحقول الرئيسية**:
- `id`: المعرف
- `content`: المحتوى
- `social_links`: روابط التواصل الاجتماعي (JSON)

---

## الخدمات (Services)

### NotificationService
**المسار**: `app/Services/NotificationService.php`

**الوظيفة**: خدمة لإرسال الإشعارات داخل التطبيق.

**الطرق**:
- `send($recipients, $title, $message, $data)`: إرسال إشعار لمستخدم واحد أو مجموعة
- `sendToAgencyRole($role, $agencyId, $title, $message, $data)`: إرسال إشعار لجميع المستخدمين بدور محدد في وكالة

**الميزات**:
- يدعم إرسال إشعارات لمستخدم واحد، مجموعة مستخدمين، أو جميع المستخدمين بدور محدد
- يحفظ معلومات المرسل تلقائياً
- يدعم بيانات إضافية (JSON)

---

## الإعدادات (Configuration)

### config/artrights.php
**الإعدادات**:
- `base_rate`: السعر الأساسي لحساب الغرامات (افتراضي: 200 DZD)
- `platform_tax_amount`: مبلغ ضريبة المنصة (افتراضي: 500 DZD)

### config/complaints.php
**الإعدادات**:
- `types`: أنواع الشكاوى المدعومة
- `targets`: الأدوار المستهدفة لكل دور مرسل

---

## المسارات (Routes)

### مسارات الويب (Web Routes)

#### المسارات العامة
- `GET /`: الصفحة الرئيسية
- `GET /dashboard`: لوحة التحكم (يتم التوجيه حسب الدور)

#### مسارات المصادقة
- `GET /register`: صفحة التسجيل
- `POST /register`: معالجة التسجيل
- `GET /login`: صفحة تسجيل الدخول
- `POST /login`: معالجة تسجيل الدخول
- `POST /logout`: تسجيل الخروج
- `GET /forgot-password`: صفحة نسيان كلمة المرور
- `POST /forgot-password`: معالجة نسيان كلمة المرور
- `GET /reset-password/{token}`: صفحة إعادة تعيين كلمة المرور
- `POST /reset-password`: معالجة إعادة تعيين كلمة المرور
- `GET /verify-email`: صفحة التحقق من البريد
- `POST /email/verification-notification`: إعادة إرسال رابط التحقق

#### مسارات Super Admin
**البادئة**: `/superadmin`

- `GET /dashboard`: لوحة التحكم
- `GET /agencies`: قائمة الوكالات
- `GET /agencies/create`: إنشاء وكالة جديدة
- `POST /agencies`: حفظ وكالة جديدة
- `GET /agencies/{id}`: عرض وكالة
- `GET /agencies/{id}/edit`: تعديل وكالة
- `PUT /agencies/{id}`: تحديث وكالة
- `DELETE /agencies/{id}`: حذف وكالة
- `GET /categories`: قائمة الفئات
- `GET /categories/create`: إنشاء فئة جديدة
- `POST /categories`: حفظ فئة جديدة
- `GET /categories/{id}/edit`: تعديل فئة
- `PUT /categories/{id}`: تحديث فئة
- `DELETE /categories/{id}`: حذف فئة
- `GET /pvs`: قائمة جميع PVs
- `GET /pvs/{id}`: عرض PV
- `GET /manage-admins`: إدارة المديرين
- `GET /manage-gestionnaires`: إدارة المدراء التنفيذيين
- `GET /notifications`: الإشعارات

#### مسارات Admin
**البادئة**: `/admin`

- `GET /dashboard`: لوحة التحكم
- `GET /manage-users`: إدارة المستخدمين
- `GET /manage-users/{id}`: عرض مستخدم
- `POST /manage-users/{id}/approve`: اعتماد فنان
- `POST /manage-users/{id}/reject`: رفض فنان
- `GET /reports-and-complaints`: الشكاوى والتقارير
- `GET /reports-and-complaints/{id}`: عرض شكوى
- `POST /reports-and-complaints/{id}/reply`: الرد على شكوى
- `GET /wallet-recharge`: طلبات إعادة شحن المحافظ
- `GET /wallet-recharge/{id}`: عرض طلب إعادة شحن
- `POST /wallet-recharge/{id}/approve`: اعتماد طلب إعادة شحن
- `POST /wallet-recharge/{id}/reject`: رفض طلب إعادة شحن
- `GET /notifications`: الإشعارات

#### مسارات Gestionnaire
**البادئة**: `/gestionnaire`

- `GET /dashboard`: لوحة التحكم
- `GET /artworks`: قائمة الأعمال الفنية
- `GET /artworks/{id}`: عرض عمل فني
- `POST /artworks/{id}/approve`: اعتماد عمل فني
- `POST /artworks/{id}/reject`: رفض عمل فني
- `GET /missions`: قائمة المهام
- `GET /missions/create`: إنشاء مهمة جديدة
- `POST /missions`: حفظ مهمة جديدة
- `GET /missions/{id}`: عرض مهمة
- `GET /missions/{id}/edit`: تعديل مهمة
- `PUT /missions/{id}`: تحديث مهمة
- `GET /pvs`: قائمة PVs
- `GET /pvs/{id}`: عرض PV
- `POST /pvs/{id}/validate-payment`: التحقق من دفعة PV
- `POST /pvs/{id}/release-funds`: إطلاق الأموال للفنانين
- `POST /pvs/{id}/finalize`: إنهاء PV
- `GET /wallet`: محفظة الوكالة
- `GET /wallet-recharge`: طلبات إعادة شحن المحافظ
- `GET /wallet-recharge/{id}`: عرض طلب إعادة شحن
- `POST /wallet-recharge/{id}/approve`: اعتماد طلب إعادة شحن
- `POST /wallet-recharge/{id}/reject`: رفض طلب إعادة شحن
- `GET /reports-and-complaints`: الشكاوى والتقارير
- `GET /reports-and-complaints/{id}`: عرض شكوى
- `POST /reports-and-complaints/{id}/reply`: الرد على شكوى
- `GET /notifications`: الإشعارات

#### مسارات Agent
**البادئة**: `/agent`

- `GET /dashboard`: لوحة التحكم
- `GET /missions`: قائمة المهام
- `GET /missions/{id}`: عرض مهمة
- `POST /missions/{id}/update-status`: تحديث حالة مهمة
- `GET /pvs`: قائمة PVs
- `GET /pvs/create`: إنشاء PV جديد
- `POST /pvs`: حفظ PV جديد
- `GET /pvs/{id}`: عرض PV
- `POST /pvs/{id}/add-device`: إضافة جهاز
- `POST /pvs/{id}/remove-device/{deviceId}`: حذف جهاز
- `GET /pvs/{id}/artworks/create`: إضافة عمل فني
- `POST /pvs/{id}/artworks`: حفظ عمل فني
- `POST /pvs/{id}/artworks/{usageId}/remove`: حذف استخدام عمل فني
- `POST /pvs/{id}/close`: إغلاق PV
- `POST /pvs/{id}/update-payment`: تحديث معلومات الدفع
- `POST /pvs/{id}/upload-payment-proof`: رفع إثبات الدفع
- `POST /pvs/{id}/upload-photos`: رفع صور
- `GET /law`: عرض القوانين
- `GET /reports-and-complaints`: الشكاوى والتقارير
- `GET /reports-and-complaints/create`: إنشاء شكوى/تقرير
- `POST /reports-and-complaints`: حفظ شكوى/تقرير
- `GET /reports-and-complaints/{id}`: عرض شكوى/تقرير
- `GET /notifications`: الإشعارات

#### مسارات Artist
**البادئة**: `/artist`

- `GET /dashboard`: لوحة التحكم
- `GET /profile`: الملف الشخصي
- `GET /profile/edit`: تعديل الملف الشخصي
- `PUT /profile`: تحديث الملف الشخصي
- `GET /artworks`: قائمة الأعمال الفنية
- `GET /artworks/create`: إنشاء عمل فني جديد
- `POST /artworks`: حفظ عمل فني جديد
- `GET /artworks/{id}`: عرض عمل فني
- `GET /artworks/{id}/edit`: تعديل عمل فني
- `PUT /artworks/{id}`: تحديث عمل فني
- `DELETE /artworks/{id}`: حذف عمل فني
- `POST /artworks/{id}/pay-platform-tax`: دفع ضريبة المنصة
- `GET /wallet`: المحفظة
- `GET /wallet/transactions`: المعاملات
- `GET /wallet/recharge`: طلب إعادة شحن
- `POST /wallet/recharge`: حفظ طلب إعادة شحن
- `GET /pvs`: سجلات PV المتعلقة
- `GET /pvs/{id}`: عرض PV
- `GET /complaints`: الشكاوى
- `GET /complaints/create`: إنشاء شكوى
- `POST /complaints`: حفظ شكوى
- `GET /complaints/{id}`: عرض شكوى
- `GET /notifications`: الإشعارات

#### مسارات الوسائط
- `GET /media/{path}`: عرض الملفات (صور، مستندات)

---

### مسارات API

#### مسارات المصادقة
- `POST /api/login`: تسجيل الدخول
- `POST /api/register`: التسجيل (للفنانين فقط)
- `POST /api/logout`: تسجيل الخروج
- `GET /api/welcome`: رسالة الترحيب
- `GET /api/agencies`: قائمة الوكالات (للتسجيل)

#### مسارات الوسائط
- `GET /api/media/{path}`: عرض الملفات (يتطلب مصادقة)

#### مسارات Artist API
**البادئة**: `/api/artist`

- `GET /profile`: الحصول على الملف الشخصي
- `PUT /profile`: تحديث الملف الشخصي
- `GET /artworks`: قائمة الأعمال الفنية
- `GET /artworks/{id}`: الحصول على عمل فني
- `POST /artworks`: إنشاء عمل فني
- `PUT /artworks/{id}`: تحديث عمل فني
- `DELETE /artworks/{id}`: حذف عمل فني
- `GET /categories`: قائمة الفئات
- `POST /artworks/{id}/pay-platform-tax`: دفع ضريبة المنصة
- `GET /wallet`: الحصول على المحفظة
- `GET /transactions`: قائمة المعاملات
- `POST /wallet/recharge`: طلب إعادة شحن
- `GET /wallet/recharge/pending`: طلبات إعادة الشحن المعلقة
- `GET /pvs`: سجلات PV المتعلقة
- `GET /complaints`: قائمة الشكاوى
- `GET /complaints/{id}`: الحصول على شكوى
- `POST /complaints`: إنشاء شكوى
- `GET /notifications`: قائمة الإشعارات
- `POST /notifications/{id}/read`: تحديد إشعار كمقروء
- `POST /notifications/read-all`: تحديد جميع الإشعارات كمقروءة
- `DELETE /notifications/{id}`: حذف إشعار
- `GET /notifications/unread-count`: عدد الإشعارات غير المقروءة
- `GET /law`: الحصول على القوانين

#### مسارات Agent API
**البادئة**: `/api/agent`

- `GET /dashboard`: إحصائيات لوحة التحكم
- `GET /profile`: الحصول على الملف الشخصي
- `PUT /profile`: تحديث الملف الشخصي
- `GET /missions`: قائمة المهام
- `GET /missions/{id}`: الحصول على مهمة
- `PUT /missions/{id}/status`: تحديث حالة مهمة
- `GET /pvs`: قائمة PVs
- `GET /pvs/{id}`: الحصول على PV
- `POST /pvs`: إنشاء PV
- `POST /pvs/{id}/devices`: إضافة جهاز
- `DELETE /pvs/{id}/devices/{deviceId}`: حذف جهاز
- `POST /pvs/{id}/artworks`: إضافة عمل فني
- `DELETE /pvs/{id}/artworks/{usageId}`: حذف استخدام عمل فني
- `POST /pvs/{id}/close`: إغلاق PV
- `PUT /pvs/{id}/payment`: تحديث معلومات الدفع
- `POST /pvs/{id}/payment-proof`: رفع إثبات الدفع
- `POST /pvs/{id}/photos`: رفع صور
- `GET /pvs/{id}/agencies/{agencyId}/artists`: الحصول على فنانين حسب الوكالة
- `GET /pvs/{id}/artists/{artistId}/artworks`: الحصول على أعمال فنية حسب الفنان
- `GET /shop-types`: قائمة أنواع المحلات
- `GET /device-types`: قائمة أنواع الأجهزة
- `GET /agencies`: قائمة الوكالات
- `GET /complaints`: قائمة الشكاوى والتقارير
- `GET /complaints/{id}`: الحصول على شكوى/تقرير
- `POST /complaints`: إنشاء شكوى/تقرير
- `GET /notifications`: قائمة الإشعارات
- `POST /notifications/{id}/read`: تحديد إشعار كمقروء
- `POST /notifications/read-all`: تحديد جميع الإشعارات كمقروءة
- `DELETE /notifications/{id}`: حذف إشعار
- `GET /notifications/unread-count`: عدد الإشعارات غير المقروءة
- `GET /law`: الحصول على القوانين
- `GET /agency-users`: الحصول على مستخدمي الوكالة (admin/gestionnaire)

#### مسارات Admin API
**البادئة**: `/api/admin`

- `POST /complaints/{id}/reply`: الرد على شكوى

---

## سير العمل (Workflows)

### 1. سير عمل تسجيل الفنان

1. **التسجيل**: الفنان يسجل حساب جديد عبر API أو الويب
   - يرفع وثيقة الهوية
   - يختار الوكالة
   - يتم تعيين الدور `artist` تلقائياً
   - الحالة: `PENDING_VALIDATION`

2. **الاعتماد**: Admin في نفس الوكالة يراجع الطلب
   - يمكنه اعتماد (`APPROVED`) أو رفض (`REJECTED`)
   - في حالة الرفض، يجب إدخال سبب الرفض

3. **بعد الاعتماد**: الفنان يمكنه:
   - رفع الأعمال الفنية
   - إدارة محفظته
   - إرسال شكاوى

### 2. سير عمل رفع العمل الفني

1. **الرفع**: الفنان يرفع عملاً فنياً
   - يختار الفئة
   - يرفع الملف (صورة، موسيقى، فيديو)
   - الحالة: `PENDING`
   - يتم حساب `platform_tax_amount` تلقائياً

2. **الاعتماد**: Gestionnaire يراجع العمل الفني
   - يمكنه اعتماد (`APPROVED`) أو رفض (`REJECTED`)
   - في حالة الرفض، يجب إدخال سبب الرفض

3. **دفع الضريبة**: بعد الاعتماد، يجب على الفنان دفع ضريبة المنصة
   - يتم خصم المبلغ من محفظة الفنان
   - يتم إضافة المبلغ لمحفظة الوكالة
   - الحالة: `platform_tax_status = PAID`

4. **التفعيل**: بعد دفع الضريبة، يمكن استخدام العمل الفني في PVs

### 3. سير عمل PV (Process-Verbal)

#### المرحلة 1: إنشاء PV
1. **إنشاء المهمة**: Gestionnaire ينشئ مهمة (Mission) ويخصصها لـ Agent
   - الحالة: `ASSIGNED`

2. **إنشاء PV**: Agent ينشئ PV جديد
   - يربطه بالمهمة (اختياري)
   - يدخل معلومات المحل (الاسم، النوع، التاريخ)
   - الحالة: `OPEN`
   - الحالة: `payment_status = PENDING`

#### المرحلة 2: إضافة البيانات
3. **إضافة الأجهزة**: Agent يضيف الأجهزة المستخدمة في المحل
   - يمكن اختيار نوع جهاز من القائمة أو إدخال يدوي
   - كل جهاز له معامل (coefficient)

4. **إضافة الأعمال الفنية**: Agent يضيف الأعمال الفنية المستخدمة
   - يختار العمل الفني (يجب أن يكون `APPROVED` و `platform_tax_status = PAID`)
   - يربطه بجهاز (اختياري)
   - يدخل عدد الساعات أو عدد مرات الاستخدام
   - يتم حساب الغرامة تلقائياً:
     ```
     Fine = (Category Coefficient) × (Device Coefficient) × (Hours/Count) × Base Rate
     ```
   - يتم تحديث `total_amount` في PV تلقائياً

5. **رفع الصور**: Agent يرفع صور كدليل (حتى 100 صورة)

#### المرحلة 3: إغلاق PV
6. **إغلاق PV**: Agent يغلق PV
   - الحالة: `CLOSED`
   - إذا كان مربوطاً بمهمة، تصبح المهمة `DONE`

#### المرحلة 4: الدفع والتحقق
7. **تأكيد الدفع**: Agent يؤكد استلام الدفعة من صاحب المحل
   - `agent_payment_confirmed = true`
   - `agent_confirmed_at = now()`
   - يدخل `cash_received_amount` (إذا كان نقداً)

8. **التحقق من الدفع**: Gestionnaire يتحقق من الدفعة
   - يتحقق من المبلغ المستلم
   - `payment_status = VALIDATED`
   - يتم إضافة المبلغ لمحفظة الوكالة
   - يتم إنشاء `AgencyWalletTransaction` (direction: IN)

#### المرحلة 5: إطلاق الأموال
9. **إطلاق الأموال**: Gestionnaire يطلق الأموال للفنانين
   - يتم حساب المبلغ لكل فنان بناءً على أعماله الفنية المستخدمة
   - يتم التحقق من رصيد محفظة الوكالة
   - يتم خصم المبلغ من محفظة الوكالة
   - يتم إضافة المبلغ لمحفظة كل فنان
   - يتم إنشاء `Transaction` لكل فنان (type: PV_PAYMENT)
   - يتم إنشاء `AgencyWalletTransaction` (direction: OUT) لكل فنان
   - `funds_released_at = now()`

#### المرحلة 6: إنهاء PV
10. **إنهاء PV**: Gestionnaire ينهي PV
    - الحالة: `FINALIZED`
    - `finalized_at = now()`
    - لا يمكن تعديل PV بعد الإنهاء

### 4. سير عمل إعادة شحن المحفظة

1. **الطلب**: الفنان يطلب إعادة شحن محفظته
   - يختار طريقة الدفع (CHEQUE, POSTAL_TRANSFER)
   - يدخل المبلغ (الحد الأدنى: 100 DZD)
   - يرفع إثبات الدفع
   - الحالة: `PENDING`

2. **الاعتماد**: Admin أو Gestionnaire يراجع الطلب
   - يمكنه اعتماد (`APPROVED`) أو رفض (`REJECTED`)
   - في حالة الرفض، يجب إدخال سبب الرفض

3. **الاعتماد**: عند الاعتماد
   - يتم إضافة المبلغ لمحفظة الفنان
   - يتم إنشاء `Transaction` (type: WALLET_RECHARGE)
   - يتم إرسال إشعار للفنان

### 5. سير عمل الشكوى/التقرير

1. **الإرسال**: المستخدم (Artist أو Agent) يرسل شكوى أو تقرير
   - يختار المستهدف (Admin أو Gestionnaire)
   - يكتب الموضوع والرسالة
   - يمكن رفع صور (حتى 5 صور)
   - يمكن إضافة رابط موقع
   - الحالة: `PENDING`

2. **الرد**: المستهدف (Admin أو Gestionnaire) يرد على الشكوى
   - يكتب الرد
   - يمكن رفع صور (حتى 5 صور)
   - الحالة: `RESOLVED`
   - `responded_at = now()`

3. **الإشعار**: يتم إرسال إشعار للمرسل بالرد

---

## حساب الغرامات

### الصيغة الأساسية
```
Fine = (Category Coefficient) × (Device Coefficient) × (Hours/Count) × Base Rate
```

### المكونات

1. **Category Coefficient (معامل الفئة)**
   - يتم تحديده من فئة العمل الفني
   - كل فئة لها معامل مختلف (مثل: موسيقى = 1.5، صورة = 1.0، فيديو = 2.0)

2. **Device Coefficient (معامل الجهاز)**
   - يتم تحديده من نوع الجهاز
   - أنواع الأجهزة: PUBLIC (عام)، COMMERCIAL (تجاري)، PERSONAL (شخصي)
   - كل نوع له معامل مختلف

3. **Hours/Count (الساعات/العدد)**
   - للأعمال الصوتية/المرئية: عدد الساعات المستخدمة (الحد الأدنى: 0.5)
   - للأعمال المرئية (الصور): عدد مرات الاستخدام (الحد الأدنى: 1)

4. **Base Rate (السعر الأساسي)**
   - قيمة ثابتة من الإعدادات (افتراضي: 200 DZD)
   - يمكن تغييرها من `config/artrights.php`

### مثال
- فئة: موسيقى (معامل: 1.5)
- جهاز: تجاري (معامل: 2.0)
- الساعات: 3
- السعر الأساسي: 200 DZD

```
Fine = 1.5 × 2.0 × 3 × 200 = 1,800 DZD
```

---

## نظام المحافظ المالية

### محفظة الفنان (Wallet)
- **الرصيد**: يبدأ من 0
- **الإضافة**: من خلال طلبات إعادة الشحن المعتمدة
- **الخصم**: عند دفع ضريبة المنصة
- **الإضافة**: عند إطلاق الأموال من PVs

### محفظة الوكالة (AgencyWallet)
- **الرصيد**: يبدأ من 0
- **الإضافة**: 
  - من ضريبة المنصة (Platform Tax)
  - من دفعات PVs المعتمدة
- **الخصم**: عند إطلاق الأموال للفنانين من PVs

### المعاملات المالية (Transactions)

#### أنواع المعاملات للفنان:
1. **PV_PAYMENT**: دفعة من PV
   - المبلغ: موجب
   - يتم إضافته للمحفظة

2. **PLATFORM_TAX**: ضريبة المنصة
   - المبلغ: سالب
   - يتم خصمه من المحفظة

3. **WALLET_RECHARGE**: إعادة شحن
   - المبلغ: موجب
   - يتم إضافته للمحفظة

#### أنواع المعاملات للوكالة:
1. **IN**: إدخال أموال
   - من ضريبة المنصة
   - من دفعات PVs

2. **OUT**: إخراج أموال
   - عند إطلاق الأموال للفنانين

---

## نظام الإشعارات

### أنواع الإشعارات

1. **artist_registration**: تسجيل فنان جديد
2. **artwork_submitted**: رفع عمل فني جديد
3. **artwork_approved**: اعتماد عمل فني
4. **artwork_rejected**: رفض عمل فني
5. **pv_opened**: فتح PV جديد
6. **pv_closed**: إغلاق PV
7. **pv_payment_validated**: التحقق من دفعة PV
8. **pv_funds_released**: إطلاق الأموال من PV
9. **wallet_recharge_request**: طلب إعادة شحن
10. **wallet_recharge_approved**: اعتماد طلب إعادة شحن
11. **wallet_recharge_rejected**: رفض طلب إعادة شحن
12. **complaint_created**: إنشاء شكوى
13. **complaint_response**: رد على شكوى
14. **report_created**: إنشاء تقرير
15. **pv_artwork_usage**: استخدام عمل فني في PV
16. **pv_agent_confirmed**: تأكيد الوكيل للدفع

### آلية الإرسال

- يتم إرسال الإشعارات تلقائياً عند حدوث أحداث معينة
- يمكن إرسال إشعار لمستخدم واحد أو مجموعة
- يمكن إرسال إشعار لجميع المستخدمين بدور محدد في وكالة
- يتم حفظ الإشعارات في قاعدة البيانات
- يمكن للمستخدمين قراءة الإشعارات عبر الويب أو API

---

## الأمان والصلاحيات

### Middleware المستخدمة

1. **auth**: التحقق من تسجيل الدخول
2. **verified**: التحقق من البريد الإلكتروني
3. **role**: التحقق من الدور (مثل: `role:admin`)
4. **permission**: التحقق من الصلاحية

### حماية المسارات

- جميع مسارات الويب (عدا العامة) محمية بـ `auth` و `verified`
- المسارات الخاصة بالأدوار محمية بـ `role`
- مسارات API محمية بـ `auth:sanctum`

### التحقق من الملكية

- Agent يمكنه فقط إدارة PVs الخاصة به
- Artist يمكنه فقط إدارة أعماله الفنية
- Admin/Gestionnaire يمكنهما فقط إدارة بيانات وكالتهما

---

## الملفات والوسائط

### مجلدات التخزين

1. **profile_photos/**: صور الملفات الشخصية
2. **artworks/**: ملفات الأعمال الفنية
3. **identity_documents/**: وثائق الهوية
4. **pv_reports/**: تقارير PV
5. **pv_evidence/**: صور أدلة PV
6. **payment_proofs/**: إثباتات الدفع
7. **wallet_recharge_proofs/**: إثباتات إعادة الشحن
8. **complaints/**: صور الشكاوى
9. **complaints/responses/**: صور ردود الشكاوى

### الوصول للملفات

- **الويب**: `/storage/{path}`
- **API**: `/api/media/{path}` (يتطلب مصادقة)

---

## قاعدة البيانات

### الجداول الرئيسية

1. **users**: المستخدمون
2. **artists**: الفنانون
3. **artworks**: الأعمال الفنية
4. **categories**: الفئات
5. **agencies**: الوكالات
6. **agents**: الوكلاء
7. **pv**: سجلات PV
8. **devices**: الأجهزة
9. **device_types**: أنواع الأجهزة
10. **pv_artwork**: استخدامات الأعمال الفنية في PVs
11. **missions**: المهام
12. **wallets**: محافظ الفنانين
13. **agency_wallets**: محافظ الوكالات
14. **transactions**: المعاملات المالية للفنانين
15. **agency_wallet_transactions**: المعاملات المالية للوكالات
16. **wallet_recharge_requests**: طلبات إعادة شحن المحافظ
17. **complaints**: الشكاوى والتقارير
18. **notifications**: الإشعارات
19. **laws**: القوانين
20. **shop_types**: أنواع المحلات
21. **footer_settings**: إعدادات التذييل

### العلاقات الرئيسية

- User → Artist (1:1)
- User → Agent (1:1)
- Agency → Artists (1:N)
- Agency → Agents (1:N)
- Agency → PVs (1:N)
- Artist → Artworks (1:N)
- Artist → Wallet (1:1)
- PV → Devices (1:N)
- PV → PVArtworks (1:N)
- PVArtwork → Artwork (N:1)
- PVArtwork → Device (N:1)
- Mission → PV (1:1)

---

## API Authentication

### Laravel Sanctum

- يستخدم Laravel Sanctum للمصادقة في API
- عند تسجيل الدخول، يتم إنشاء token
- يجب إرسال token في header: `Authorization: Bearer {token}`
- عند تسجيل الخروج، يتم حذف token

### الأدوار المدعومة في API

- **Artist**: يمكنه الوصول لـ Artist API
- **Agent**: يمكنه الوصول لـ Agent API
- **Admin**: يمكنه الوصول لـ Admin API (محدود)

---

## الخلاصة

**ArtRights** هو نظام شامل لإدارة حقوق الملكية الفنية الرقمية يتضمن:

1. **إدارة متعددة المستويات**: Super Admin → Admin → Gestionnaire → Agent/Artist
2. **نظام PV متكامل**: من الإنشاء حتى الإنهاء مع تتبع المدفوعات
3. **نظام محافظ مالية**: للفنانين والوكالات مع تتبع جميع المعاملات
4. **نظام شكاوى**: للتواصل بين المستخدمين
5. **نظام إشعارات**: لإعلام المستخدمين بالأحداث المهمة
6. **واجهات برمجية (API)**: للوصول من التطبيقات المحمولة
7. **حساب تلقائي للغرامات**: بناءً على معاملات قابلة للتخصيص

النظام مصمم ليكون مرناً وقابلاً للتوسع مع الحفاظ على الأمان والصلاحيات المناسبة لكل دور.

