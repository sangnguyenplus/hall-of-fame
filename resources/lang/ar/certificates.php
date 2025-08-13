<?php

return [
    'public_certificates' => 'الشهادات العامة',
    'certificate' => [
        'title' => 'شهادة تقدير',
        'subtitle' => 'التميز في البحث الأمني',
        'company_name' => 'هو زيد آي إس',
        'page_title' => 'شهادة - هو زيد آي إس',
        'signed_page_title' => 'شهادة بحث أمني موقعة',
        
        // Certificate content
        'awarded_to' => 'تُمنح هذه الشهادة لباحث الأمان',
        'for_contribution' => 'لمساهمته المتميزة في الأمن السيبراني من خلال الكشف المسؤول عن الثغرات الأمنية',
        'digitally_signed' => 'تم توقيع هذه الشهادة رقمياً والتحقق من صحتها',
        'pgp_verified' => 'تم التحقق من التوقيع الرقمي',
        
        // Certificate details
        'details' => [
            'date' => 'التاريخ',
            'certificate_id' => 'الشهادة',
            'vulnerability' => 'الثغرة الأمنية',
            'type' => 'النوع',
            'discovery' => 'الاكتشاف',
            'acknowledged' => 'الاعتراف',
            'verify' => 'التحقق',
            'researcher' => 'الباحث',
            'email' => 'البريد الإلكتروني',
        ],
        
        // Signature section
        'signature' => [
            'authorized_by' => 'مُعتمدة من',
            'digital_signature' => 'التوقيع الرقمي',
            'verification_note' => 'يمكن التحقق من هذه الشهادة باستخدام رابط التحقق المقدم أو التوقيع الرقمي.',
        ],
        
        // Security notices
        'security' => [
            'verification_required' => 'التحقق من الشهادة مطلوب للمصادقة',
            'pgp_key_info' => 'التحقق باستخدام مفتاح PGP',
            'certificate_hash' => 'قيمة تشفير الشهادة',
        ],
    ],

    // رسائل الكونترولر
    'certificate_already_exists' => 'الشهادة موجودة بالفعل لهذا التقرير',
    'certificate_generated_successfully' => 'تم إنشاء الشهادة بنجاح',
    'failed_to_generate' => 'فشل في إنشاء الشهادة',
    'bulk_generated_successfully' => 'تم إنشاء :count شهادات بنجاح',
    'bulk_generation_failed' => 'فشل في الإنشاء المجمع',
    'certificate_regenerated_successfully' => 'تم إعادة إنشاء الشهادة بنجاح',
    'failed_to_regenerate' => 'فشل في إعادة إنشاء الشهادة',
    'certificate_file_not_found' => 'ملف الشهادة غير موجود',
    'certificate_deleted_successfully' => 'تم حذف الشهادة بنجاح',
    'failed_to_delete' => 'فشل في حذف الشهادة',

    // ترجمات واجهة الإدارة
    'admin' => [
        'certificate_details' => 'تفاصيل الشهادة',
        'back_to_list' => 'العودة إلى القائمة',
        'certificate_information' => 'معلومات الشهادة',
        'certificate_id' => 'رقم الشهادة',
        'created_date' => 'تاريخ الإنشاء',
        'status' => 'الحالة',
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'is_signed' => 'موقعة',
        'yes' => 'نعم',
        'no' => 'لا',
        'vulnerability_report' => 'تقرير الثغرة الأمنية',
        'title' => 'العنوان',
        'researcher' => 'الباحث',
        'email' => 'البريد الإلكتروني',
        'severity' => 'درجة الخطورة',
        'n_a' => 'غير متوفر',
        'published' => 'منشور',
        'no_vulnerability_report' => 'لا يوجد تقرير ثغرة أمنية مرتبط.',
        'description' => 'الوصف',
        'actions' => 'الإجراءات',
        'download_pdf' => 'تنزيل PDF',
        'verify_certificate' => 'التحقق من الشهادة',
        'view_pdf' => 'عرض PDF',
    ],

    // ترجمات الواجهة العامة
    'public' => [
        'hall_of_fame_certificates' => 'شهادات قاعة الشهرة',
        'browse_certificates_info' => 'تصفح شهادات البحث الأمني الممنوحة للباحثين الذين ساهموا في الأمن السيبراني من خلال الكشف المسؤول عن الثغرات الأمنية.',
        'certificate' => 'الشهادة',
        'vulnerability' => 'الثغرة الأمنية',
        'researcher' => 'الباحث',
        'issued' => 'تاريخ الإصدار',
        'severity' => 'درجة الخطورة',
        'view_details' => 'عرض التفاصيل',
        'download_pdf' => 'تنزيل PDF',
        'verify' => 'التحقق',
        'no_certificates_available' => 'لا توجد شهادات متاحة',
        'no_certificates_issued' => 'لم يتم إصدار أي شهادات بعد.',
    ],
];
