import './bootstrap';
import './echo';

// إنشاء Audio Context للصوت
let audioContext;
let notificationSound;

// إنشاء صوت نقرة برمجياً
function createNotificationSound() {
    if (!audioContext) {
        audioContext = new (window.AudioContext || window.webkitAudioContext)();
    }
    
    return function playNotificationSound() {
        try {
            // إنشاء صوت نقرة مناسب
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            // ربط العقد
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            // إعداد الصوت
            oscillator.frequency.setValueAtTime(800, audioContext.currentTime); // تردد عالي
            oscillator.frequency.exponentialRampToValueAtTime(400, audioContext.currentTime + 0.1); // انخفاض تدريجي
            
            // إعداد مستوى الصوت
            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
            
            // تشغيل الصوت
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.3);
            
        } catch (error) {
            console.log('Could not play notification sound:', error);
        }
    };
}

// إنشاء صوت نقرة أكثر تطوراً
function createAdvancedNotificationSound() {
    if (!audioContext) {
        audioContext = new (window.AudioContext || window.webkitAudioContext)();
    }
    
    return function playAdvancedNotificationSound() {
        try {
            // النقرة الأولى
            const osc1 = audioContext.createOscillator();
            const gain1 = audioContext.createGain();
            
            osc1.connect(gain1);
            gain1.connect(audioContext.destination);
            
            osc1.frequency.setValueAtTime(800, audioContext.currentTime);
            gain1.gain.setValueAtTime(0.15, audioContext.currentTime);
            gain1.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
            
            osc1.start(audioContext.currentTime);
            osc1.stop(audioContext.currentTime + 0.1);
            
            // النقرة الثانية (صدى)
            const osc2 = audioContext.createOscillator();
            const gain2 = audioContext.createGain();
            
            osc2.connect(gain2);
            gain2.connect(audioContext.destination);
            
            osc2.frequency.setValueAtTime(600, audioContext.currentTime + 0.15);
            gain2.gain.setValueAtTime(0.1, audioContext.currentTime + 0.15);
            gain2.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.25);
            
            osc2.start(audioContext.currentTime + 0.15);
            osc2.stop(audioContext.currentTime + 0.25);
            
        } catch (error) {
            console.log('Could not play advanced notification sound:', error);
        }
    };
}

// إنشاء صوت WhatsApp-like
function createWhatsAppLikeSound() {
    if (!audioContext) {
        audioContext = new (window.AudioContext || window.webkitAudioContext)();
    }
    
    return function playWhatsAppLikeSound() {
        try {
            // إنشاء نقرة مشابهة لـ WhatsApp
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            const filter = audioContext.createBiquadFilter();
            
            // ربط العقد
            oscillator.connect(filter);
            filter.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            // إعداد المرشح
            filter.type = 'lowpass';
            filter.frequency.setValueAtTime(2000, audioContext.currentTime);
            
            // إعداد الذبذبة
            oscillator.frequency.setValueAtTime(880, audioContext.currentTime);
            oscillator.frequency.exponentialRampToValueAtTime(440, audioContext.currentTime + 0.1);
            
            // إعداد مستوى الصوت
            gainNode.gain.setValueAtTime(0.2, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
            
            // تشغيل الصوت
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.2);
            
        } catch (error) {
            console.log('Could not play WhatsApp-like sound:', error);
        }
    };
}

// إعداد الصوت عند التحميل
document.addEventListener('DOMContentLoaded', () => {
    // تحميل نوع الصوت المحفوظ أو استخدام الافتراضي
    const savedSoundType = localStorage.getItem('chatSoundType') || 'whatsapp';
    
    // إنشاء الصوت المناسب حسب النوع المحفوظ
    switch(savedSoundType) {
        case 'simple':
            notificationSound = createNotificationSound();
            break;
        case 'advanced':
            notificationSound = createAdvancedNotificationSound();
            break;
        case 'whatsapp':
        default:
            notificationSound = createWhatsAppLikeSound();
            break;
    }
    
    // تفعيل AudioContext عند أول تفاعل مع الصفحة
    const enableAudio = () => {
        if (audioContext && audioContext.state === 'suspended') {
            audioContext.resume();
        }
        document.removeEventListener('click', enableAudio);
        document.removeEventListener('keydown', enableAudio);
        document.removeEventListener('touchstart', enableAudio);
    };
    
    document.addEventListener('click', enableAudio);
    document.addEventListener('keydown', enableAudio);
    document.addEventListener('touchstart', enableAudio);
    
    const userId = document.querySelector('meta[name="user-id"]')?.content;

    if (window.Echo && window.Livewire && userId) {
        console.log('Setting up Echo listener for user:', userId);
        
        window.Echo.private(`users.${userId}`)
            .notification((notification) => {
                console.log('Received notification:', notification);
                
                // إرسال الإشعار إلى جميع components المهتمة
                if (notification.type === 'App\\Notifications\\MessageSent') {
                    // تشغيل الصوت عند استلام رسالة جديدة
                    if (notificationSound) {
                        notificationSound();
                    }
                    
                    // رسالة جديدة - تحديث قائمة الدردشة والـ navigation
                    window.Livewire.dispatch('messageReceived', {
                        conversation_id: notification.conversation_id,
                        message_id: notification.message_id,
                        user_id: notification.user_id,
                        receiver_id: notification.receiver_id
                    });
                    
                    // تحديث عدد الرسائل غير المقروءة في الـ navigation
                    window.Livewire.dispatch('updateUnreadCount');
                    
                    // إظهار browser notification إذا كان متاحاً
                    showNotification('رسالة جديدة', 'لديك رسالة جديدة');
                    
                } else if (notification.type === 'App\\Notifications\\MessageRead') {
                    // رسالة تم قراءتها - تحديث قائمة الدردشة والـ navigation
                    window.Livewire.dispatch('messageRead', {
                        conversation_id: notification.conversation_id
                    });
                    
                    // تحديث عدد الرسائل غير المقروءة في الـ navigation
                    window.Livewire.dispatch('updateUnreadCount');
                }
            });
    }
});

// إضافة global listener للـ Livewire events
document.addEventListener('livewire:init', () => {
    console.log('Livewire initialized');
    
    // الاستماع للـ events المخصصة
    Livewire.on('refresh', () => {
        console.log('Refresh event received');
    });
    
    Livewire.on('updateUnreadCount', () => {
        console.log('Update unread count event received');
    });
});

// إضافة support للـ notifications في الـ browser
if ('Notification' in window) {
    if (Notification.permission === 'default') {
        Notification.requestPermission();
    }
}

// إضافة function لإظهار browser notifications
function showNotification(title, body, icon = null) {
    if (Notification.permission === 'granted') {
        new Notification(title, {
            body: body,
            icon: icon || '/favicon.ico',
            tag: 'chat-notification'
        });
    }
}

// إضافة global functions للتحكم في الصوت
window.ChatNotification = {
    playSound: () => {
        if (notificationSound) {
            notificationSound();
        }
    },
    
    // تغيير نوع الصوت
    setSoundType: (type) => {
        switch(type) {
            case 'simple':
                notificationSound = createNotificationSound();
                break;
            case 'advanced':
                notificationSound = createAdvancedNotificationSound();
                break;
            case 'whatsapp':
                notificationSound = createWhatsAppLikeSound();
                break;
            default:
                notificationSound = createWhatsAppLikeSound();
        }
    },
    
    // تشغيل صوت تجريبي
    testSound: () => {
        if (notificationSound) {
            notificationSound();
        }
    }
};