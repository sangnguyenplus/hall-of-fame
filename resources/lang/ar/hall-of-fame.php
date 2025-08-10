<?php

return [
    'name' => 'قاعة المشاهير',
    'description' => 'باحثو الأمان الذين ساعدوا في تحسين أمننا',

    'vulnerability-reports' => [
        'name' => 'تقارير الثغرات الأمنية',
        'create' => 'إنشاء تقرير جديد',
        'edit' => 'تعديل التقرير',
        'form' => [
            'title' => 'العنوان',
            'vulnerability_type' => 'نوع الثغرة',
            'endpoint' => 'نقطة النهاية المتأثرة',
            'description' => 'الوصف',
            'impact' => 'التأثير',
            'steps_to_reproduce' => 'خطوات إعادة الإنتاج',
            'suggested_fix' => 'الإصلاح المقترح',
            'researcher_name' => 'اسم الباحث',
            'researcher_email' => 'البريد الإلكتروني للباحث',
            'researcher_bio' => 'نبذة عن الباحث',
            'status' => 'الحالة',
        ],
    ],
];
