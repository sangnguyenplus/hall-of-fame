<?php

return [
    // عام
    'name' => 'قاعة المشاهير',
    'description' => 'باحثو الأمان الذين ساعدوا في تحسين أمننا',
    'hall_of_fame_description' => 'شكراً لجميع باحثي الأمان الذين ساعدوا في الحفاظ على أمان منصتنا.',
    'submit_vulnerability' => 'تقديم ثغرة أمنية',

    // الصفحات العامة
    'hall_of_fame' => 'قاعة المشاهير',
    'vulnerability_disclosure' => 'سياسة الإفصاح عن الثغرات',
    'recognition_program' => 'برنامج التقدير',
    'thank_you' => 'شكراً لك',
    'thank_you_message' => 'شكراً لك على إرسال تقرير الثغرة الأمنية. سيقوم فريق الأمان لدينا بمراجعته في أقرب وقت ممكن.',
    'reference_id' => 'رقم المرجع',
    'view_hall_of_fame' => 'عرض قاعة المشاهير',
    'view_details' => 'عرض التفاصيل',
    'back_to_hall_of_fame' => 'العودة إلى قاعة المشاهير',
    'reported_on' => 'تم الإبلاغ في',
    'no_reports_found' => 'لم يتم العثور على تقارير ثغرات أمنية.',
    'no_reports_found_for_year' => 'لم يتم العثور على تقارير ثغرات أمنية لعام :year.',

    // صفحة التقديم
    'Submit a Vulnerability' => 'تقديم ثغرة أمنية',
    'Hall of Fame' => 'قاعة المشاهير',
    'Submission Guidelines' => 'إرشادات التقديم',
    'Provide detailed information about the vulnerability, including steps to reproduce' => 'قدّم معلومات مفصلة حول الثغرة، بما في ذلك خطوات إعادة الإنتاج',
    'Do not include any sensitive personal data of users or employees' => 'لا تقم بتضمين أي بيانات شخصية حساسة للمستخدمين أو الموظفين',
    'Never attempt to access, modify, or delete data without permission' => 'لا تحاول الوصول إلى البيانات أو تعديلها أو حذفها بدون إذن',
    'Include screenshots or videos if they help explain the issue' => 'قم بإرفاق لقطات شاشة أو مقاطع فيديو إذا كانت تساعد في شرح المشكلة',
    'Be respectful and patient as we investigate your report' => 'كن محترماً وصبوراً أثناء تحقيقنا في تقريرك',
    'For sensitive reports, consider <a href=":url" target="_blank">using our PGP key</a> to encrypt your submission' => 'للتقارير الحساسة، فكّر في <a href=":url" target="_blank">استخدام مفتاح PGP الخاص بنا</a> لتشفير تقريرك',
    'Login Required' => 'تسجيل الدخول مطلوب',
    'You need to be logged in to submit a vulnerability report' => 'يجب تسجيل الدخول لتقديم تقرير عن ثغرة أمنية',
    'Login' => 'تسجيل الدخول',
    'Register' => 'إنشاء حساب',

    // حقول النموذج (للواجهة العامة)
    'form_title' => 'العنوان',
    'form_title_placeholder' => 'أدخل عنوان التقرير',
    'form_vulnerability_type' => 'نوع الثغرة',
    'form_vulnerability_type_placeholder' => 'مثال: XSS، حقن SQL، CSRF',
    'form_endpoint' => 'نقطة النهاية المتأثرة',
    'form_endpoint_placeholder' => 'مثال: /api/users أو https://example.com/contact',
    'form_description' => 'الوصف',
    'form_description_placeholder' => 'صف الثغرة الأمنية بالتفصيل',
    'form_impact' => 'التأثير',
    'form_impact_placeholder' => 'ما التأثير المحتمل لهذه الثغرة؟',
    'form_steps_to_reproduce' => 'خطوات إعادة الإنتاج',
    'form_steps_to_reproduce_placeholder' => 'قدّم خطوات تفصيلية لإعادة إنتاج الثغرة',
    'form_suggested_fix' => 'إصلاح مقترح',
    'form_suggested_fix_placeholder' => 'إذا كان لديك اقتراحات حول كيفية إصلاح هذه المشكلة',
    'form_attachments' => 'المرفقات',
    'form_researcher_info' => 'معلومات الباحث',
    'form_researcher_name' => 'اسم الباحث',
    'form_researcher_name_placeholder' => 'اسمك الكامل',
    'form_researcher_email' => 'بريد الباحث الإلكتروني',
    'form_researcher_email_placeholder' => 'عنوان بريدك الإلكتروني',
    'form_researcher_bio' => 'نبذة عن الباحث',
    'form_researcher_bio_placeholder' => 'وصف موجز عن نفسك (اختياري)',
    'form_privacy_policy' => 'لقد قرأت ووافقت على سياسة الخصوصية',
    'form_submit' => 'إرسال التقرير',
    'form_attachments_hint' => 'ارفع لقطات شاشة أو مقاطع فيديو أو ملفات أخرى تساعد في توضيح الثغرة (حد أقصى 10 ميجابايت لكل ملف)',
    'anonymous' => 'مجهول',

    // ترجمات الشهادات
    'certificate' => [
        'title' => 'شهادة تقدير',
        'signed_title' => 'شهادة موثقة',
        'subtitle' => 'التميز في بحوث الأمان',
        'signed_subtitle' => 'شهادة تقدير بحوث الأمان موقعة بـ PGP',
        'company_name' => 'هو زيد آي إس',
        'awarded_to' => 'تُمنح هذه الشهادة بموجب هذا لباحث الأمان',
        'signed_awarded_to' => 'تُقدم هذه الشهادة الموقعة رقمياً لـ',
        'excellence_text' => 'لمساهمته المتميزة في الأمن السيبراني من خلال الإفصاح المسؤول عن الثغرات',
        'signed_excellence_text' => 'للتميز في بحوث الأمن السيبراني والإفصاح المسؤول',
        'pgp_verified' => '✓ موثق بـ PGP',
        'pgp_signature_header' => '🔐 توقيع رقمي بـ PGP',
        'vulnerability_label' => 'الثغرة:',
        'type_label' => 'النوع:',
        'discovery_label' => 'الاكتشاف:',
        'acknowledged_label' => 'الاعتراف:',
        'signed_label' => 'التوقيع:',
        'verify_label' => 'التحقق:',
        'certificate_id' => 'الشهادة:',
        'certificate_id_label' => 'الشهادة',
        'date_label' => 'التاريخ:',
        'signature_line' => 'فريق أمان هو زيد آي إس',
        'signature_subtitle' => 'شهادة موقعة رقمياً',
        'verify_note' => 'تحقق في: :url | التوقيع الرقمي بـ PGP مُرفق',
    ],

    // Status options
    'status_reported' => 'تم الإبلاغ',
    'status_validated' => 'تم التحقق',
    'status_in_progress' => 'قيد الإصلاح',
    'status_fixed' => 'تم الإصلاح',
    'status_published' => 'منشور',

    // Admin table columns
    'column_name' => 'الاسم',
    'column_email' => 'البريد الإلكتروني',
    'column_reports' => 'التقارير',
    'column_created_at' => 'تاريخ الإنشاء',
    'column_updated_at' => 'تاريخ التحديث',

    // Permissions
    'permission_vulnerability_reports' => 'تقارير الثغرات الأمنية',
    'permission_create' => 'إنشاء',
    'permission_edit' => 'تعديل',
    'permission_delete' => 'حذف',

    // Messages
    'message_create_success' => 'تم الإنشاء بنجاح',
    'message_update_success' => 'تم التحديث بنجاح',
    'message_delete_success' => 'تم الحذف بنجاح',
    'message_error' => 'خطأ',
    'approve_success' => 'تم قبول التقرير ونشره بنجاح',
    'reject_success' => 'تم رفض التقرير',

    // Researchers section
    'researchers_name' => 'الباحثون',
    'researchers_create' => 'إنشاء باحث جديد',
    'researchers_edit' => 'تعديل الباحث',

    // Vulnerability Reports section
    'vulnerability_reports_name' => 'تقارير الثغرات الأمنية',
    'vulnerability_reports_create' => 'إنشاء تقرير جديد',
    'vulnerability_reports_edit' => 'تعديل التقرير',
    'my_reports' => 'تقاريري',

    // Form fields
    'form_status' => 'الحالة',
    'form_status_info' => 'معلومات الحالة',
    'form_is_published' => 'منشور',
    'form_admin_note' => 'ملاحظة المشرف',
    'form_admin_note_placeholder' => 'ملاحظة خاصة للمشرفين فقط',
];
