<?php

namespace Database\Seeders;

use App\Models\Law;
use Illuminate\Database\Seeder;

class LawSeeder extends Seeder
{
    public function run(): void
    {
        // English Law Content
        Law::create([
            'language' => 'english',
            'title' => 'Copyright Law and Regulations',
            'notice' => 'These regulations govern the use and protection of intellectual property rights in Algeria.',
            'sections' => [
                [
                    'title' => 'Article 1: Copyright Protection',
                    'content' => 'Copyright protection extends to original works of authorship including literary, artistic, musical, and dramatic works.',
                    'items' => [
                        'Protection applies automatically upon creation',
                        'No formal registration required',
                        'Duration: Author\'s life plus 50 years',
                        'Moral rights are perpetual and inalienable'
                    ]
                ],
                [
                    'title' => 'Article 2: Rights of Authors',
                    'content' => 'Authors have exclusive rights to exploit their works and authorize others to do so.',
                    'items' => [
                        'Reproduction rights',
                        'Distribution rights',
                        'Public performance rights',
                        'Adaptation and translation rights'
                    ]
                ],
                [
                    'title' => 'Article 3: Exceptions and Limitations',
                    'content' => 'Certain uses of copyrighted works are permitted without authorization.',
                    'highlight' => true,
                    'items' => [
                        'Private and domestic use',
                        'Educational and research purposes',
                        'Library and archive reproduction',
                        'News reporting and criticism'
                    ]
                ]
            ]
        ]);

        // Arabic Law Content
        Law::create([
            'language' => 'arabic',
            'title' => 'قانون حقوق الملكية الفكرية واللوائح',
            'notice' => 'تحكم هذه اللوائح استخدام وحماية حقوق الملكية الفكرية في الجزائر.',
            'sections' => [
                [
                    'title' => 'المادة 1: حماية حقوق الطبع والنشر',
                    'content' => 'تمتد حماية حقوق الطبع والنشر إلى الأعمال الأصلية للمؤلفين بما في ذلك الأعمال الأدبية والفنية والموسيقية والدرامية.',
                    'items' => [
                        'تطبق الحماية تلقائياً عند الإنشاء',
                        'لا يتطلب تسجيل رسمي',
                        'المدة: حياة المؤلف زائد 50 عاماً',
                        'الحقوق الأدبية دائمة وغير قابلة للتنازل'
                    ]
                ],
                [
                    'title' => 'المادة 2: حقوق المؤلفين',
                    'content' => 'للمؤلفين حقوق حصرية في استغلال أعمالهم وتفويض الآخرين للقيام بذلك.',
                    'items' => [
                        'حقوق النسخ والإعادة',
                        'حقوق التوزيع',
                        'حقوق الأداء العام',
                        'حقوق التعديل والترجمة'
                    ]
                ],
                [
                    'title' => 'المادة 3: الاستثناءات والقيود',
                    'content' => 'يُسمح ببعض الاستخدامات للأعمال المحمية بحقوق الطبع والنشر دون إذن.',
                    'highlight' => true,
                    'items' => [
                        'الاستخدام الخاص والمنزلي',
                        'الأغراض التعليمية والبحثية',
                        'إعادة إنتاج المكتبات والمحفوظات',
                        'التقارير الإخبارية والنقد'
                    ]
                ]
            ]
        ]);

        // French Law Content
        Law::create([
            'language' => 'french',
            'title' => 'Loi sur le Droit d\'Auteur et les Règlements',
            'notice' => 'Ces réglementations régissent l\'utilisation et la protection des droits de propriété intellectuelle en Algérie.',
            'sections' => [
                [
                    'title' => 'Article 1: Protection du Droit d\'Auteur',
                    'content' => 'La protection du droit d\'auteur s\'étend aux œuvres originales d\'auteur incluant les œuvres littéraires, artistiques, musicales et dramatiques.',
                    'items' => [
                        'La protection s\'applique automatiquement dès la création',
                        'Aucune inscription formelle requise',
                        'Durée: Vie de l\'auteur plus 50 ans',
                        'Les droits moraux sont perpétuels et inaliénables'
                    ]
                ],
                [
                    'title' => 'Article 2: Droits des Auteurs',
                    'content' => 'Les auteurs ont des droits exclusifs d\'exploiter leurs œuvres et d\'autoriser autrui à le faire.',
                    'items' => [
                        'Droits de reproduction',
                        'Droits de distribution',
                        'Droits de représentation publique',
                        'Droits d\'adaptation et de traduction'
                    ]
                ],
                [
                    'title' => 'Article 3: Exceptions et Limitations',
                    'content' => 'Certaines utilisations des œuvres protégées par le droit d\'auteur sont autorisées sans autorisation.',
                    'highlight' => true,
                    'items' => [
                        'Usage privé et domestique',
                        'Fins éducatives et de recherche',
                        'Reproduction des bibliothèques et archives',
                        'Reportages d\'actualités et critique'
                    ]
                ]
            ]
        ]);
    }
}