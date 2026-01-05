<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class SupportController extends Controller
{
    /**
     * Display the support page
     */
    public function support(Request $request)
    {
        $lang = $request->get('lang', 'en');
        return view('support', compact('lang'));
    }

    /**
     * Display the help page with role information
     */
    public function help(Request $request)
    {
        // Require authentication
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Get language preference (default: en)
        $lang = $request->get('lang', 'en');
        
        // Get current user's role
        $user = auth()->user();
        $userRole = $user->roles->first();
        
        // Get all roles with their permissions
        $roles = Role::with('permissions')->get();
        
        // Map roles to English and Arabic names and descriptions
        $roleInfo = [
            'super_admin' => [
                'en' => [
                    'name' => 'Super Admin',
                    'description' => 'Super Admin has the highest level of permissions in the system',
                    'permissions' => [
                        'manage admins' => 'Manage Administrators',
                        'manage gestionnaires' => 'Manage Managers',
                        'manage categories' => 'Manage Categories',
                        'manage agencies' => 'Manage Agencies',
                        'manage pvs' => 'Manage PVs (Process-Verbals)',
                        'view dashboard' => 'View Dashboard',
                        'view notifications' => 'View Notifications',
                    ]
                ],
                'ar' => [
                    'name' => 'المدير العام',
                    'description' => 'المدير العام لديه أعلى مستوى من الصلاحيات في النظام',
                    'permissions' => [
                        'manage admins' => 'إدارة المديرين',
                        'manage gestionnaires' => 'إدارة المدراء',
                        'manage categories' => 'إدارة الفئات',
                        'manage agencies' => 'إدارة الوكالات',
                        'manage pvs' => 'إدارة محاضر الضبط',
                        'view dashboard' => 'عرض لوحة التحكم',
                        'view notifications' => 'عرض الإشعارات',
                    ]
                ]
            ],
            'admin' => [
                'en' => [
                    'name' => 'Administrator',
                    'description' => 'Administrator manages users, artists, and complaints',
                    'permissions' => [
                        'approve artists' => 'Approve Artists',
                        'reject artists' => 'Reject Artists',
                        'manage users' => 'Manage Users',
                        'manage complaints' => 'Manage Complaints',
                        'manage gestionnaires (category)' => 'Manage Managers (by Category)',
                        'view dashboard' => 'View Dashboard',
                        'view notifications' => 'View Notifications',
                    ]
                ],
                'ar' => [
                    'name' => 'مدير',
                    'description' => 'المدير يدير المستخدمين والفنانين والشكاوى',
                    'permissions' => [
                        'approve artists' => 'الموافقة على الفنانين',
                        'reject artists' => 'رفض الفنانين',
                        'manage users' => 'إدارة المستخدمين',
                        'manage complaints' => 'إدارة الشكاوى',
                        'manage gestionnaires (category)' => 'إدارة المدراء (حسب الفئة)',
                        'view dashboard' => 'عرض لوحة التحكم',
                        'view notifications' => 'عرض الإشعارات',
                    ]
                ]
            ],
            'gestionnaire' => [
                'en' => [
                    'name' => 'Manager',
                    'description' => 'Manager manages artworks, agencies, and missions',
                    'permissions' => [
                        'approve artworks' => 'Approve Artworks',
                        'reject artworks' => 'Reject Artworks',
                        'manage artworks' => 'Manage Artworks',
                        'send agents' => 'Send Agents',
                        'manage pvs' => 'Manage PVs',
                        'manage agencies' => 'Manage Agencies',
                        'manage revenues' => 'Manage Revenues',
                        'view dashboard' => 'View Dashboard',
                        'view notifications' => 'View Notifications',
                    ]
                ],
                'ar' => [
                    'name' => 'مدير',
                    'description' => 'المدير يدير الأعمال الفنية والوكالات والمهام',
                    'permissions' => [
                        'approve artworks' => 'الموافقة على الأعمال الفنية',
                        'reject artworks' => 'رفض الأعمال الفنية',
                        'manage artworks' => 'إدارة الأعمال الفنية',
                        'send agents' => 'إرسال الوكلاء',
                        'manage pvs' => 'إدارة محاضر الضبط',
                        'manage agencies' => 'إدارة الوكالات',
                        'manage revenues' => 'إدارة الإيرادات',
                        'view dashboard' => 'عرض لوحة التحكم',
                        'view notifications' => 'عرض الإشعارات',
                    ]
                ]
            ],
            'agent' => [
                'en' => [
                    'name' => 'Agent',
                    'description' => 'Agent creates PVs and manages payments',
                    'permissions' => [
                        'create pv' => 'Create PV',
                        'view pvs' => 'View PVs',
                        'close pv' => 'Close PV',
                        'view law' => 'View Law',
                        'manage payments' => 'Manage Payments',
                        'view dashboard' => 'View Dashboard',
                        'view notifications' => 'View Notifications',
                    ]
                ],
                'ar' => [
                    'name' => 'وكيل',
                    'description' => 'الوكيل يقوم بإنشاء محاضر الضبط وإدارة المدفوعات',
                    'permissions' => [
                        'create pv' => 'إنشاء محضر ضبط',
                        'view pvs' => 'عرض محاضر الضبط',
                        'close pv' => 'إغلاق محضر ضبط',
                        'view law' => 'عرض القانون',
                        'manage payments' => 'إدارة المدفوعات',
                        'view dashboard' => 'عرض لوحة التحكم',
                        'view notifications' => 'عرض الإشعارات',
                    ]
                ]
            ],
            'artist' => [
                'en' => [
                    'name' => 'Artist',
                    'description' => 'Artist can manage their artworks and wallet',
                    'permissions' => [
                        'view profile' => 'View Profile',
                        'edit profile' => 'Edit Profile',
                        'create artwork' => 'Create Artwork',
                        'edit artwork' => 'Edit Artwork',
                        'delete artwork' => 'Delete Artwork',
                        'view wallet' => 'View Wallet',
                        'view related pvs' => 'View Related PVs',
                        'submit complaint' => 'Submit Complaint',
                        'view dashboard' => 'View Dashboard',
                        'view notifications' => 'View Notifications',
                    ]
                ],
                'ar' => [
                    'name' => 'فنان',
                    'description' => 'الفنان يمكنه إدارة أعماله الفنية ومحفظته',
                    'permissions' => [
                        'view profile' => 'عرض الملف الشخصي',
                        'edit profile' => 'تعديل الملف الشخصي',
                        'create artwork' => 'إنشاء عمل فني',
                        'edit artwork' => 'تعديل عمل فني',
                        'delete artwork' => 'حذف عمل فني',
                        'view wallet' => 'عرض المحفظة',
                        'view related pvs' => 'عرض محاضر الضبط ذات الصلة',
                        'submit complaint' => 'إرسال شكوى',
                        'view dashboard' => 'عرض لوحة التحكم',
                        'view notifications' => 'عرض الإشعارات',
                    ]
                ]
            ],
            'user' => [
                'en' => [
                    'name' => 'User',
                    'description' => 'New user account pending approval. Must register, select the state agency, upload artist verification documents, and wait for admin approval. Cannot access the platform or log in until approved.',
                    'permissions' => []
                ],
                'ar' => [
                    'name' => 'مستخدم',
                    'description' => 'حساب مستخدم جديد في انتظار الموافقة. يجب التسجيل واختيار الوكالة الولائية التابعة لها ورفع الوثائق اللازمة لإثبات أنه فنان وانتظار موافقة المدير. لا يمكنه الدخول للمنصة أو تسجيل الدخول حتى يتم قبوله.',
                    'permissions' => []
                ]
            ],
        ];

        // Detailed role information with rights and responsibilities
        $roleDetails = [
            'super_admin' => [
                'en' => [
                    'rights' => [
                        'Full system access and control',
                        'Create and manage administrators',
                        'Manage all agencies and their staff',
                        'Configure system categories and settings',
                        'View and manage all PVs across the platform',
                        'Access comprehensive system reports',
                    ],
                    'responsibilities' => [
                        'Ensure system security and stability',
                        'Oversee all administrative operations',
                        'Manage user access and permissions',
                        'Monitor system performance',
                        'Handle critical system decisions',
                    ]
                ],
                'ar' => [
                    'rights' => [
                        'الوصول الكامل والتحكم في النظام',
                        'إنشاء وإدارة المديرين',
                        'إدارة جميع الوكالات وموظفيها',
                        'تكوين فئات النظام والإعدادات',
                        'عرض وإدارة جميع محاضر الضبط في المنصة',
                        'الوصول إلى تقارير النظام الشاملة',
                    ],
                    'responsibilities' => [
                        'ضمان أمان واستقرار النظام',
                        'الإشراف على جميع العمليات الإدارية',
                        'إدارة وصول المستخدمين والصلاحيات',
                        'مراقبة أداء النظام',
                        'معالجة القرارات الحرجة للنظام',
                    ]
                ]
            ],
            'admin' => [
                'en' => [
                    'rights' => [
                        'Approve or reject artist registrations',
                        'Manage user accounts and profiles',
                        'Handle complaints and reports',
                        'Assign gestionnaires to categories',
                        'View all PVs in the agency',
                        'Access financial transaction reports',
                    ],
                    'responsibilities' => [
                        'Review and approve artist applications',
                        'Respond to user complaints promptly',
                        'Ensure compliance with platform policies',
                        'Coordinate with gestionnaires',
                        'Monitor agency operations',
                    ]
                ],
                'ar' => [
                    'rights' => [
                        'الموافقة على تسجيلات الفنانين أو رفضها',
                        'إدارة حسابات المستخدمين والملفات الشخصية',
                        'معالجة الشكاوى والتقارير',
                        'تعيين المدراء للفئات',
                        'عرض جميع محاضر الضبط في الوكالة',
                        'الوصول إلى تقارير المعاملات المالية',
                    ],
                    'responsibilities' => [
                        'مراجعة والموافقة على طلبات الفنانين',
                        'الرد على شكاوى المستخدمين بسرعة',
                        'ضمان الامتثال لسياسات المنصة',
                        'التنسيق مع المدراء',
                        'مراقبة عمليات الوكالة',
                    ]
                ]
            ],
            'gestionnaire' => [
                'en' => [
                    'rights' => [
                        'Approve or reject artwork submissions',
                        'Create and assign missions to agents',
                        'Manage PVs and verify violations',
                        'Handle wallet recharge requests',
                        'View agency financial balance',
                        'Manage agency operations',
                    ],
                    'responsibilities' => [
                        'Review artwork submissions for compliance',
                        'Coordinate field inspections through agents',
                        'Verify and process PVs',
                        'Manage agency revenues and payments',
                        'Ensure proper documentation',
                    ]
                ],
                'ar' => [
                    'rights' => [
                        'الموافقة على تقديمات الأعمال الفنية أو رفضها',
                        'إنشاء وتعيين المهام للوكلاء',
                        'إدارة محاضر الضبط والتحقق من الانتهاكات',
                        'معالجة طلبات شحن المحفظة',
                        'عرض الرصيد المالي للوكالة',
                        'إدارة عمليات الوكالة',
                    ],
                    'responsibilities' => [
                        'مراجعة تقديمات الأعمال الفنية للامتثال',
                        'تنسيق عمليات التفتيش الميداني من خلال الوكلاء',
                        'التحقق من محاضر الضبط ومعالجتها',
                        'إدارة إيرادات الوكالة والمدفوعات',
                        'ضمان التوثيق المناسب',
                    ]
                ]
            ],
            'agent' => [
                'en' => [
                    'rights' => [
                        'Create Process-Verbals (PVs) for violations',
                        'Add devices and artworks to PVs',
                        'Close completed PVs',
                        'Upload payment proofs',
                        'View assigned missions',
                        'Access legal reference materials',
                    ],
                    'responsibilities' => [
                        'Conduct field inspections as assigned',
                        'Document violations accurately',
                        'Collect evidence and information',
                        'Follow legal procedures',
                        'Submit complete PVs on time',
                    ]
                ],
                'ar' => [
                    'rights' => [
                        'إنشاء محاضر الضبط للانتهاكات',
                        'إضافة الأجهزة والأعمال الفنية إلى محاضر الضبط',
                        'إغلاق محاضر الضبط المكتملة',
                        'رفع إثباتات الدفع',
                        'عرض المهام المعينة',
                        'الوصول إلى المواد المرجعية القانونية',
                    ],
                    'responsibilities' => [
                        'إجراء عمليات التفتيش الميداني حسب التعيين',
                        'توثيق الانتهاكات بدقة',
                        'جمع الأدلة والمعلومات',
                        'اتباع الإجراءات القانونية',
                        'تقديم محاضر الضبط الكاملة في الوقت المحدد',
                    ]
                ]
            ],
            'artist' => [
                'en' => [
                    'rights' => [
                        'Register and manage artworks',
                        'View wallet balance and transactions',
                        'Submit complaints for violations',
                        'Track related PVs',
                        'Update profile information',
                        'Access legal reference materials',
                    ],
                    'responsibilities' => [
                        'Provide accurate artwork information',
                        'Pay required platform taxes',
                        'Respond to PV notifications',
                        'Maintain updated profile',
                        'Follow platform guidelines',
                    ]
                ],
                'ar' => [
                    'rights' => [
                        'تسجيل وإدارة الأعمال الفنية',
                        'عرض رصيد المحفظة والمعاملات',
                        'تقديم شكاوى للانتهاكات',
                        'تتبع محاضر الضبط ذات الصلة',
                        'تحديث معلومات الملف الشخصي',
                        'الوصول إلى المواد المرجعية القانونية',
                    ],
                    'responsibilities' => [
                        'تقديم معلومات دقيقة عن الأعمال الفنية',
                        'دفع ضرائب المنصة المطلوبة',
                        'الرد على إشعارات محاضر الضبط',
                        'الحفاظ على الملف الشخصي محدث',
                        'اتباع إرشادات المنصة',
                    ]
                ]
            ],
            'user' => [
                'en' => [
                    'rights' => [
                        'Register a new account',
                        'Select the state agency (Wilaya) you belong to',
                        'Upload required documents to prove you are an artist',
                        'Wait for admin approval',
                        'Receive email notification about approval or rejection status',
                    ],
                    'responsibilities' => [
                        'Create a new account with accurate information',
                        'Choose the correct state agency (Wilaya) during registration',
                        'Upload all required documents proving you are an artist',
                        'Wait for email notification regarding approval or rejection',
                        'Check email for approval status and reason if rejected',
                        'Cannot access the platform or log in until account is approved',
                    ]
                ],
                'ar' => [
                    'rights' => [
                        'تسجيل حساب جديد',
                        'اختيار الوكالة الولائية التابعة لها',
                        'رفع الوثائق اللازمة لإثبات أنك فنان',
                        'انتظار موافقة المدير',
                        'استلام إيميل لإعلامك بحالة الموافقة أو الرفض',
                    ],
                    'responsibilities' => [
                        'إنشاء حساب جديد بمعلومات دقيقة',
                        'اختيار الوكالة الولائية الصحيحة أثناء التسجيل',
                        'رفع جميع الوثائق المطلوبة لإثبات أنك فنان',
                        'انتظار إيميل لإعلامك بالموافقة أو الرفض',
                        'التحقق من الإيميل لمعرفة حالة الموافقة والسبب في حالة الرفض',
                        'لا يمكنه الدخول للمنصة أو تسجيل الدخول حتى يتم قبول الحساب',
                    ]
                ]
            ],
        ];

        // Get current user's role details
        $currentRoleDetails = null;
        if ($userRole && isset($roleDetails[$userRole->name])) {
            $currentRoleDetails = $roleDetails[$userRole->name][$lang] ?? $roleDetails[$userRole->name]['en'];
        }

        // Build role data with actual permissions from database
        $rolesData = [];
        foreach ($roles as $role) {
            $roleName = $role->name;
            if (isset($roleInfo[$roleName])) {
                $roleLang = $roleInfo[$roleName][$lang] ?? $roleInfo[$roleName]['en'];
                $rolesData[] = [
                    'name' => $roleLang['name'],
                    'key' => $roleName,
                    'description' => $roleLang['description'],
                    'permissions' => $role->permissions->map(function ($permission) use ($roleLang, $roleName) {
                        $permissionKey = $permission->name;
                        return [
                            'key' => $permissionKey,
                            'name' => $roleLang['permissions'][$permissionKey] ?? $permissionKey,
                        ];
                    })->toArray(),
                ];
            }
        }

        return view('help', compact('rolesData', 'lang', 'currentRoleDetails', 'userRole'));
    }
}

