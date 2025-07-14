<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعدادات الصوت</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<style>
    .sound-wave {
        animation: wave 0.5s ease-in-out infinite alternate;
    }

    @keyframes wave {
        0% {
            transform: scaleY(1);
        }

        100% {
            transform: scaleY(0.3);
        }
    }

    .sound-indicator {
        display: flex;
        align-items: center;
        gap: 2px;
        height: 20px;
    }

    .sound-bar {
        width: 3px;
        background: #3b82f6;
        border-radius: 2px;
        animation: soundBars 0.8s ease-in-out infinite;
    }

    .sound-bar:nth-child(1) {
        height: 10px;
        animation-delay: 0s;
    }

    .sound-bar:nth-child(2) {
        height: 15px;
        animation-delay: 0.1s;
    }

    .sound-bar:nth-child(3) {
        height: 20px;
        animation-delay: 0.2s;
    }

    .sound-bar:nth-child(4) {
        height: 15px;
        animation-delay: 0.3s;
    }

    .sound-bar:nth-child(5) {
        height: 10px;
        animation-delay: 0.4s;
    }

    @keyframes soundBars {

        0%,
        100% {
            transform: scaleY(1);
        }

        50% {
            transform: scaleY(0.3);
        }
    }

    .playing .sound-bar {
        animation-play-state: running;
    }

    .not-playing .sound-bar {
        animation-play-state: paused;
        transform: scaleY(0.3);
    }
</style>

<body class="bg-gray-50 p-4">
    <div class="max-w-md mx-auto">
        <!-- Sound Settings Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div
                    class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 14.142M5 7l7-7 7 7" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">إعدادات الصوت</h3>
                    <p class="text-sm text-gray-500">اختر نوع صوت الإشعارات</p>
                </div>
            </div>

            <!-- Sound Type Selection -->
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer"
                    onclick="selectSoundType('simple')">
                    <div class="flex items-center gap-3">
                        <input type="radio" name="soundType" value="simple" class="w-4 h-4 text-blue-600">
                        <span class="font-medium text-gray-700">صوت بسيط</span>
                    </div>
                    <button onclick="testSound('simple')"
                        class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15a2 2 0 002-2V9a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 4H8a2 2 0 00-2 2v3a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer"
                    onclick="selectSoundType('advanced')">
                    <div class="flex items-center gap-3">
                        <input type="radio" name="soundType" value="advanced" class="w-4 h-4 text-blue-600">
                        <span class="font-medium text-gray-700">صوت متقدم</span>
                    </div>
                    <button onclick="testSound('advanced')"
                        class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15a2 2 0 002-2V9a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 4H8a2 2 0 00-2 2v3a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>

                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors cursor-pointer border-2 border-blue-200"
                    onclick="selectSoundType('whatsapp')">
                    <div class="flex items-center gap-3">
                        <input type="radio" name="soundType" value="whatsapp" checked class="w-4 h-4 text-blue-600">
                        <span class="font-medium text-gray-700">صوت واتساب</span>
                        <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-full">مُوصى به</span>
                    </div>
                    <button onclick="testSound('whatsapp')"
                        class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15a2 2 0 002-2V9a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 4H8a2 2 0 00-2 2v3a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Sound Indicator -->
            <div class="mt-4 p-3 bg-gray-50 rounded-xl">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">حالة الصوت:</span>
                    <div class="sound-indicator not-playing" id="soundIndicator">
                        <div class="sound-bar"></div>
                        <div class="sound-bar"></div>
                        <div class="sound-bar"></div>
                        <div class="sound-bar"></div>
                        <div class="sound-bar"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h4 class="text-lg font-bold text-gray-800 mb-4">اختبار الصوت</h4>

            <div class="space-y-3">
                <button onclick="simulateMessage()"
                    class="w-full p-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-200 font-medium">
                    <div class="flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        محاكاة استلام رسالة
                    </div>
                </button>

                <button onclick="window.ChatNotification?.testSound()"
                    class="w-full p-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-medium">
                    <div class="flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 14.142M5 7l7-7 7 7" />
                        </svg>
                        اختبار الصوت الحالي
                    </div>
                </button>
            </div>
        </div>

        <!-- Info Section -->
        <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mt-0.5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h5 class="font-medium text-blue-800 mb-1">معلومات مهمة</h5>
                    <p class="text-sm text-blue-700">• سيتم تشغيل الصوت عند استلام أي رسالة جديدة</p>
                    <p class="text-sm text-blue-700">• يعمل الصوت في جميع الصفحات</p>
                    <p class="text-sm text-blue-700">• تأكد من تفعيل الصوت في المتصفح</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // تحديد نوع الصوت
        function selectSoundType(type) {
            // تحديث Radio buttons
            const radios = document.querySelectorAll('input[name="soundType"]');
            radios.forEach(radio => {
                radio.checked = radio.value === type;
            });

            // تحديث الصوت
            if (window.ChatNotification) {
                window.ChatNotification.setSoundType(type);
            }

            // حفظ الإعداد
            localStorage.setItem('chatSoundType', type);

            // إظهار رسالة تأكيد
            showConfirmation('تم تحديث نوع الصوت');
        }

        // اختبار الصوت
        function testSound(type) {
            const indicator = document.getElementById('soundIndicator');

            // إظهار المؤشر
            indicator.className = 'sound-indicator playing';

            // تشغيل الصوت
            if (window.ChatNotification) {
                window.ChatNotification.setSoundType(type);
                window.ChatNotification.testSound();
            }

            // إخفاء المؤشر بعد ثانية
            setTimeout(() => {
                indicator.className = 'sound-indicator not-playing';
            }, 1000);
        }

        // محاكاة استلام رسالة
        function simulateMessage() {
            const indicator = document.getElementById('soundIndicator');

            // إظهار المؤشر
            indicator.className = 'sound-indicator playing';

            // تشغيل الصوت
            if (window.ChatNotification) {
                window.ChatNotification.playSound();
            }

            // إظهار إشعار
            if (Notification.permission === 'granted') {
                new Notification('رسالة جديدة', {
                    body: 'هذه رسالة تجريبية',
                    icon: '/favicon.ico'
                });
            }

            // إخفاء المؤشر بعد ثانية
            setTimeout(() => {
                indicator.className = 'sound-indicator not-playing';
            }, 1000);

            showConfirmation('تم تشغيل صوت الرسالة');
        }

        // إظهار رسالة تأكيد
        function showConfirmation(message) {
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
            notification.textContent = message;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);

            setTimeout(() => {
                notification.style.transform = 'translateX(full)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // تحميل الإعدادات المحفوظة
        document.addEventListener('DOMContentLoaded', () => {
            const savedSoundType = localStorage.getItem('chatSoundType') || 'whatsapp';
            selectSoundType(savedSoundType);

            // طلب إذن الإشعارات
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }
        });
    </script>
</body>

</html>