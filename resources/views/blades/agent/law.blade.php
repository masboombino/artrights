<x-allthepages-layout pageTitle="Legal Reference">
    <div class="space-y-6">
        <!-- English Section -->
        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <h2 class="text-2xl font-semibold mb-4" style="color: #193948;">Copyright Protection Law - English</h2>
            <p class="mb-4" style="color: #193948;">
                <strong>Important Notice:</strong> This document explains your legal obligations when using protected artistic works. Please read carefully.
            </p>
            
            <div class="space-y-4">
                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948;">
                    <h3 class="text-lg font-semibold mb-3" style="color: #193948;">What is Protected?</h3>
                    <p class="mb-2" style="color: #193948;">
                        All artistic works (music, images, videos, etc.) are protected by copyright law. Using them without proper authorization is illegal.
                    </p>
                </div>

                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948;">
                    <h3 class="text-lg font-semibold mb-3" style="color: #193948;">Your Obligations</h3>
                    <ul class="list-disc space-y-2 ml-6" style="color: #193948;">
                        <li>You must declare all protected artworks you use in your establishment.</li>
                        <li>You must provide proof of payment for the usage rights.</li>
                        <li>You must allow agents to inspect your devices and record usage.</li>
                        <li>You must pay the calculated fine based on the usage.</li>
                    </ul>
                </div>

                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948;">
                    <h3 class="text-lg font-semibold mb-3" style="color: #193948;">How Fines Are Calculated</h3>
                    <p class="mb-2" style="color: #193948;">The fine is calculated using the following formula:</p>
                    <div class="p-3 rounded font-mono text-center" style="background-color: #dbeafe; border: 1px solid #3b82f6;">
                        <p class="text-base" style="color: #193948;">
                            <strong>Fine = {(Category Coefficient) × (Device Coefficient) × (Hours/Count) × Base Rate}</strong>
                        </p>
                    </div>
                    <ul class="list-disc space-y-2 ml-6 mt-3" style="color: #193948;">
                        <li><strong>Category Coefficient:</strong> Depends on the type of artwork (music, image, video, etc.)</li>
                        <li><strong>Device Coefficient:</strong> Depends on your device type (public, commercial, personal)</li>
                        <li><strong>Hours/Count:</strong> Duration of use (for audio/video) or number of uses (for images)</li>
                        <li><strong>Base Rate:</strong> {{ number_format(config('artrights.base_rate', 200), 2) }} DZD (current rate)</li>
                    </ul>
                </div>

                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948;">
                    <h3 class="text-lg font-semibold mb-3" style="color: #193948;">Payment Methods</h3>
                    <p class="mb-2" style="color: #193948;">You can pay the fine using:</p>
                    <ul class="list-disc space-y-2 ml-6" style="color: #193948;">
                        <li><strong>Cash:</strong> Payment in cash to the agent</li>
                        <li><strong>Card:</strong> Bank card payment</li>
                        <li><strong>Cheque:</strong> Bank cheque payment</li>
                    </ul>
                    <p class="mt-3" style="color: #193948;">
                        <strong>Note:</strong> The PV (Procès-Verbal) remains pending until payment is validated by the agency head office.
                    </p>
                </div>

                <div class="p-4 rounded" style="background-color: #fee2e2; border: 2px solid #ef4444;">
                    <h3 class="text-lg font-semibold mb-2" style="color: #991b1b;">⚠️ Legal Consequences</h3>
                    <p style="color: #991b1b;">
                        Failure to comply with these regulations may result in legal action, additional fines, and potential closure of your establishment.
                    </p>
                </div>
            </div>
        </div>

        <!-- Arabic Section -->
        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <h2 class="text-2xl font-semibold mb-4" style="color: #193948; direction: rtl; text-align: right;">قانون حماية حقوق المؤلف - العربية</h2>
            <p class="mb-4" style="color: #193948; direction: rtl; text-align: right;">
                <strong>إشعار مهم:</strong> يوضح هذا المستند التزاماتك القانونية عند استخدام الأعمال الفنية المحمية. يرجى القراءة بعناية.
            </p>
            
            <div class="space-y-4" style="direction: rtl;">
                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948;">
                    <h3 class="text-lg font-semibold mb-3" style="color: #193948; text-align: right;">ما الذي يتم حمايته؟</h3>
                    <p class="mb-2" style="color: #193948; text-align: right;">
                        جميع الأعمال الفنية (الموسيقى، الصور، الفيديوهات، إلخ) محمية بموجب قانون حقوق المؤلف. استخدامها دون إذن مناسب غير قانوني.
                    </p>
                </div>

                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948;">
                    <h3 class="text-lg font-semibold mb-3" style="color: #193948; text-align: right;">التزاماتك</h3>
                    <ul class="list-disc space-y-2 ml-6" style="color: #193948; text-align: right;">
                        <li>يجب عليك الإعلان عن جميع الأعمال الفنية المحمية التي تستخدمها في مؤسستك.</li>
                        <li>يجب عليك تقديم إثبات دفع حقوق الاستخدام.</li>
                        <li>يجب عليك السماح للوكلاء بفحص أجهزتك وتسجيل الاستخدام.</li>
                        <li>يجب عليك دفع الغرامة المحسوبة بناءً على الاستخدام.</li>
                    </ul>
                </div>

                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948;">
                    <h3 class="text-lg font-semibold mb-3" style="color: #193948; text-align: right;">كيف يتم حساب الغرامات</h3>
                    <p class="mb-2" style="color: #193948; text-align: right;">يتم حساب الغرامة باستخدام الصيغة التالية:</p>
                    <div class="p-3 rounded font-mono text-center" style="background-color: #dbeafe; border: 1px solid #3b82f6;">
                        <p class="text-base" style="color: #193948;">
                            <strong>الغرامة = {(معامل الفئة) × (معامل الجهاز) × (الساعات/العدد) × السعر الأساسي}</strong>
                        </p>
                    </div>
                    <ul class="list-disc space-y-2 ml-6 mt-3" style="color: #193948; text-align: right;">
                        <li><strong>معامل الفئة:</strong> يعتمد على نوع العمل الفني (موسيقى، صورة، فيديو، إلخ)</li>
                        <li><strong>معامل الجهاز:</strong> يعتمد على نوع جهازك (عام، تجاري، شخصي)</li>
                        <li><strong>الساعات/العدد:</strong> مدة الاستخدام (للصوت/الفيديو) أو عدد الاستخدامات (للصور)</li>
                        <li><strong>السعر الأساسي:</strong> {{ number_format(config('artrights.base_rate', 200), 2) }} دج (السعر الحالي)</li>
                    </ul>
                </div>

                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948;">
                    <h3 class="text-lg font-semibold mb-3" style="color: #193948; text-align: right;">طرق الدفع</h3>
                    <p class="mb-2" style="color: #193948; text-align: right;">يمكنك دفع الغرامة باستخدام:</p>
                    <ul class="list-disc space-y-2 ml-6" style="color: #193948; text-align: right;">
                        <li><strong>نقداً:</strong> الدفع نقداً للوكيل</li>
                        <li><strong>بطاقة:</strong> الدفع ببطاقة بنكية</li>
                        <li><strong>شيك:</strong> الدفع بشيك بنكي</li>
                    </ul>
                    <p class="mt-3" style="color: #193948; text-align: right;">
                        <strong>ملاحظة:</strong> يبقى PV (محضر) معلقاً حتى يتم التحقق من الدفع من قبل المكتب الرئيسي للوكالة.
                    </p>
                </div>

                <div class="p-4 rounded" style="background-color: #fee2e2; border: 2px solid #ef4444;">
                    <h3 class="text-lg font-semibold mb-2" style="color: #991b1b; text-align: right;">⚠️ العواقب القانونية</h3>
                    <p style="color: #991b1b; text-align: right;">
                        عدم الامتثال لهذه اللوائح قد يؤدي إلى إجراءات قانونية وغرامات إضافية وإمكانية إغلاق مؤسستك.
                    </p>
                </div>
            </div>
        </div>

        <!-- French Section -->
        <div class="rounded-lg shadow p-6" style="background-color: #F3EBDD;">
            <h2 class="text-2xl font-semibold mb-4" style="color: #193948;">Loi sur la Protection du Droit d'Auteur - Français</h2>
            <p class="mb-4" style="color: #193948;">
                <strong>Avis Important :</strong> Ce document explique vos obligations légales lors de l'utilisation d'œuvres artistiques protégées. Veuillez lire attentivement.
            </p>
            
            <div class="space-y-4">
                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948;">
                    <h3 class="text-lg font-semibold mb-3" style="color: #193948;">Qu'est-ce qui est Protégé ?</h3>
                    <p class="mb-2" style="color: #193948;">
                        Toutes les œuvres artistiques (musique, images, vidéos, etc.) sont protégées par le droit d'auteur. Les utiliser sans autorisation appropriée est illégal.
                    </p>
                </div>

                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948;">
                    <h3 class="text-lg font-semibold mb-3" style="color: #193948;">Vos Obligations</h3>
                    <ul class="list-disc space-y-2 ml-6" style="color: #193948;">
                        <li>Vous devez déclarer toutes les œuvres protégées que vous utilisez dans votre établissement.</li>
                        <li>Vous devez fournir une preuve de paiement pour les droits d'utilisation.</li>
                        <li>Vous devez permettre aux agents d'inspecter vos appareils et d'enregistrer l'utilisation.</li>
                        <li>Vous devez payer l'amende calculée en fonction de l'utilisation.</li>
                    </ul>
                </div>

                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948;">
                    <h3 class="text-lg font-semibold mb-3" style="color: #193948;">Comment les Amendes sont Calculées</h3>
                    <p class="mb-2" style="color: #193948;">L'amende est calculée à l'aide de la formule suivante :</p>
                    <div class="p-3 rounded font-mono text-center" style="background-color: #dbeafe; border: 1px solid #3b82f6;">
                        <p class="text-base" style="color: #193948;">
                            <strong>Amende = {(Coefficient de Catégorie) × (Coefficient d'Appareil) × (Heures/Nombre) × Taux de Base}</strong>
                        </p>
                    </div>
                    <ul class="list-disc space-y-2 ml-6 mt-3" style="color: #193948;">
                        <li><strong>Coefficient de Catégorie :</strong> Dépend du type d'œuvre (musique, image, vidéo, etc.)</li>
                        <li><strong>Coefficient d'Appareil :</strong> Dépend du type de votre appareil (public, commercial, personnel)</li>
                        <li><strong>Heures/Nombre :</strong> Durée d'utilisation (pour audio/vidéo) ou nombre d'utilisations (pour images)</li>
                        <li><strong>Taux de Base :</strong> {{ number_format(config('artrights.base_rate', 200), 2) }} DZD (taux actuel)</li>
                    </ul>
                </div>

                <div class="p-4 rounded" style="background-color: #ffffff; border: 2px solid #193948;">
                    <h3 class="text-lg font-semibold mb-3" style="color: #193948;">Modes de Paiement</h3>
                    <p class="mb-2" style="color: #193948;">Vous pouvez payer l'amende en utilisant :</p>
                    <ul class="list-disc space-y-2 ml-6" style="color: #193948;">
                        <li><strong>Espèces :</strong> Paiement en espèces à l'agent</li>
                        <li><strong>Carte :</strong> Paiement par carte bancaire</li>
                        <li><strong>Chèque :</strong> Paiement par chèque bancaire</li>
                    </ul>
                    <p class="mt-3" style="color: #193948;">
                        <strong>Note :</strong> Le PV (Procès-Verbal) reste en attente jusqu'à ce que le paiement soit validé par le bureau central de l'agence.
                    </p>
                </div>

                <div class="p-4 rounded" style="background-color: #fee2e2; border: 2px solid #ef4444;">
                    <h3 class="text-lg font-semibold mb-2" style="color: #991b1b;">⚠️ Conséquences Légales</h3>
                    <p style="color: #991b1b;">
                        Le non-respect de ces règlements peut entraîner des poursuites judiciaires, des amendes supplémentaires et la fermeture potentielle de votre établissement.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-allthepages-layout>
