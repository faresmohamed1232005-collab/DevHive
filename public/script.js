// ===================== HAMBURGER MENU =====================
const hamburger = document.getElementById('hamburger');
const mobileMenu = document.getElementById('mobileMenu');

// التحقق إن العناصر موجودة قبل إضافة الـ event listeners
if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', function(e) {
        e.stopPropagation();
        hamburger.classList.toggle('active');
        mobileMenu.classList.toggle('active');
    });

    // إغلاق المنيو لما يضغط على أي لينك جواه
    const mobileLinks = document.querySelectorAll('.mobile-menu a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            const href = this.getAttribute('href');

            hamburger.classList.remove('active');
            mobileMenu.classList.remove('active');

            // Scroll للـ section المطلوب
            if (href && href.startsWith('#')) {
                const target = document.querySelector(href);
                if (target) {
                    setTimeout(function() {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 200);
                }
            } else if (href) {
                // لو رابط صفحة تانية يروح عليها
                window.location.href = href;
            }
        });
    });

    // إغلاق المنيو لما يضغط برا الهيدر
    document.addEventListener('click', function(e) {
        const isClickInsideHeader = e.target.closest('header');
        if (!isClickInsideHeader && mobileMenu.classList.contains('active')) {
            hamburger.classList.remove('active');
            mobileMenu.classList.remove('active');
        }
    });
}

// ===================== HEADER SCROLL EFFECT =====================
const header = document.getElementById('header');

if (header) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
}

// ===================== SMOOTH SCROLL FOR ALL LINKS =====================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        // تجاهل لينكات المنيو المتنقل عشان متتعالجش مرتين
        if (this.closest('.mobile-menu')) return;

        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// ===================== BUTTON CLICK EFFECTS =====================
document.addEventListener('DOMContentLoaded', function() {

    // Ripple effect على أزرار الـ enroll
    document.querySelectorAll('.btn-enroll, .btn-primary, .btn-signup').forEach(button => {
        button.addEventListener('click', function(e) {
            // Ripple
            const ripple = document.createElement('span');
            const rect   = this.getBoundingClientRect();
            const size   = Math.max(rect.width, rect.height);
            const x      = e.clientX - rect.left - size / 2;
            const y      = e.clientY - rect.top  - size / 2;

            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                background: rgba(255,255,255,0.5);
                border-radius: 50%;
                left: ${x}px;
                top: ${y}px;
                pointer-events: none;
                animation: rippleAnimation 0.6s ease-out;
            `;

            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600);
        });
    });

    // ===================== BOOKING MODAL =====================
    const bookingModal  = document.getElementById('bookingModal');
    const bookingButton = document.getElementById('bookingButton');

    // دالة بتتحقق من حالة الحجز وبتحدث الزرار
    function updateBookingButton() {
        const savedBooked = localStorage.getItem('isBooked') || localStorage.getItem('edulux_booked');
        if (!bookingButton) return;

        if (savedBooked === 'true') {
            // لو مسجل — يبقى زرار مشاهدة الكورس
            bookingButton.innerHTML = '<i class="fas fa-play"></i> <span>مشاهدة الكورس</span>';
        } else {
            // لو مش مسجل — يبقى زرار احجز مقعدك الان
            bookingButton.innerHTML = '<i class="fas fa-ticket-alt"></i> <span>احجز مقعدك الان</span>';
        }
    }

    // بيحدث الزرار وقت تحميل الصفحة
    updateBookingButton();

    if (bookingButton && bookingModal) {
        bookingButton.addEventListener('click', function(e) {
            e.stopPropagation();
            const savedBooked = localStorage.getItem('isBooked') || localStorage.getItem('edulux_booked');
            if (savedBooked === 'true') {
                window.location.href = '/course.html'; // يروح صفحة الكورس
            } else {
                openBookingModal(); // يفتح مودال الحجز
            }
        });
    }

    // ===================== LOGOUT BUTTON =====================
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            logout();
        });
    }
});

// ===================== BOOKING MODAL FUNCTIONS =====================
function openBookingModal() {
    const bookingModal = document.getElementById('bookingModal');
    if (bookingModal) {
        bookingModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function closeBookingModal() {
    const bookingModal = document.getElementById('bookingModal');
    if (bookingModal) {
        bookingModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// إغلاق المودال لما يضغط برا
window.addEventListener('click', function(event) {
    const bookingModal = document.getElementById('bookingModal');
    if (bookingModal && event.target === bookingModal) {
        closeBookingModal();
    }
});

// ===================== BOOKING STEPS =====================
function goToStep2() {
    const fullName    = document.getElementById('fullName')?.value.trim();
    const phoneNumber = document.getElementById('phoneNumber')?.value.trim();
    const email       = document.getElementById('email')?.value.trim();

    if (!fullName || !phoneNumber || !email) {
        showBookingToast('الرجاء ملء جميع الحقول', 'error');
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showBookingToast('البريد الإلكتروني غير صحيح', 'error');
        return;
    }

    if (!/^\d{10,}$/.test(phoneNumber.replace(/\D/g, ''))) {
        showBookingToast('رقم الهاتف غير صحيح', 'error');
        return;
    }

    window.bookingData = { fullName, phoneNumber, email };

    document.getElementById('step1')?.classList.remove('active');
    document.getElementById('step2')?.classList.add('active');
    showBookingToast('تم حفظ البيانات بنجاح ✓', 'success');
}

function goToStep3() {
    const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');

    if (!paymentMethod) {
        showBookingToast('الرجاء اختيار طريقة دفع', 'error');
        return;
    }

    window.bookingData.paymentMethod = paymentMethod.value;

    document.getElementById('step2')?.classList.remove('active');
    document.getElementById('step3')?.classList.add('active');
    showBookingToast('تم تأكيد طريقة الدفع ✓', 'success');
}

function goToStep4() {
    document.getElementById('step3')?.classList.remove('active');
    document.getElementById('step4')?.classList.add('active');
}

function goToStep1() {
    document.getElementById('step2')?.classList.remove('active');
    document.getElementById('step1')?.classList.add('active');
}

function completeBooking() {
    const username        = document.getElementById('username')?.value.trim();
    const password        = document.getElementById('password')?.value.trim();
    const confirmPassword = document.getElementById('confirmPassword')?.value.trim();

    if (!username || !password || !confirmPassword) {
        showBookingToast('الرجاء ملء جميع الحقول', 'error');
        return;
    }

    if (username.length < 3) {
        showBookingToast('اسم المستخدم يجب أن يكون 3 أحرف على الأقل', 'error');
        return;
    }

    if (password.length < 6) {
        showBookingToast('كلمة المرور يجب أن تكون 6 أحرف على الأقل', 'error');
        return;
    }

    if (password !== confirmPassword) {
        showBookingToast('كلمات المرور غير متطابقة', 'error');
        return;
    }

    window.bookingData.username = username;
    window.bookingData.password = password;

    // حفظ البيانات في localStorage
    localStorage.setItem('userBookingData', JSON.stringify(window.bookingData));
    localStorage.setItem('isBooked', 'true');

    const bookingButton = document.getElementById('bookingButton');
    if (bookingButton) {
        bookingButton.innerHTML = '<i class="fas fa-play"></i> <span id="buttonText">مشاهدة الكورس</span>';
    }

    showBookingToast('تم إنشاء حسابك بنجاح! 🎉', 'success');

    setTimeout(() => {
        window.location.href = '/course.html'; // الانتقال لصفحة الكورس
    }, 1500);
}

// ===================== LOGOUT =====================
function logout() {
    // مسح كل بيانات اليوزر والحجز
    localStorage.removeItem('userBookingData');
    localStorage.removeItem('isBooked');
    localStorage.removeItem('edulux_user');
    localStorage.removeItem('edulux_booked');

    showBookingToast('تم تسجيل الخروج بنجاح', 'success');

    // بعد ثانية يروح للصفحة الرئيسية
    // الزرار هيرجع "احجز مقعدك الان" تلقائياً لأن isBooked اتمسح
    setTimeout(() => {
        window.location.href = '/index.html';
    }, 1000);
}

// ===================== TOAST NOTIFICATIONS =====================
function showToast(message) {
    showBookingToast(message, 'info');
}

function showBookingToast(message, type = 'info') {
    const existingToast = document.querySelector('.booking-toast');
    if (existingToast) existingToast.remove();

    const toast = document.createElement('div');
    toast.className = 'booking-toast';

    const bgColor = type === 'success' ? '#4ade80' 
                  : type === 'error'   ? '#ef4444' 
                  : '#3b82f6';

    toast.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: ${bgColor};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        z-index: 10001;
        font-weight: 600;
        animation: slideInToast 0.4s ease-out;
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
    `;

    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'slideOutToast 0.4s ease-out';
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}

// ===================== ANIMATIONS =====================
const style = document.createElement('style');
style.textContent = `
    @keyframes rippleAnimation {
        to { transform: scale(4); opacity: 0; }
    }
    @keyframes slideInToast {
        from { transform: translateX(400px); opacity: 0; }
        to   { transform: translateX(0);     opacity: 1; }
    }
    @keyframes slideOutToast {
        from { transform: translateX(0);     opacity: 1; }
        to   { transform: translateX(400px); opacity: 0; }
    }
`;
document.head.appendChild(style);

// ===================== COURSE CARD ANIMATION ON SCROLL =====================
const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -100px 0px' };

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animation = 'slideInLeft 0.6s ease-out forwards';
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

document.querySelectorAll('.course-card, .testimonial-card').forEach(card => {
    card.style.opacity = '0';
    observer.observe(card);
});

// ===================== COUNTER ANIMATION =====================
function animateCounter(element, target, duration = 2000) {
    let current = 0;
    const increment = target / (duration / 16);
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            const originalText = element.getAttribute('data-original');
            if (originalText?.includes('+'))      element.textContent = target + '+';
            else if (originalText?.includes('%')) element.textContent = target + '%';
            else                                  element.textContent = target;
            clearInterval(timer);
        } else {
            const originalText = element.getAttribute('data-original');
            const suffix = originalText?.includes('+') ? '+' : originalText?.includes('%') ? '%' : '';
            element.textContent = Math.floor(current) + suffix;
        }
    }, 16);
}

const statsObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.querySelectorAll('.stat-number').forEach(stat => {
                const originalText = stat.textContent;
                stat.setAttribute('data-original', originalText);
                const numericPart = parseInt(originalText.replace(/[^\d]/g, ''));
                if (!isNaN(numericPart) && numericPart > 0) {
                    animateCounter(stat, numericPart);
                }
            });
            statsObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.5 });

const statsSection = document.querySelector('.stats-section');
if (statsSection) statsObserver.observe(statsSection);

// ===================== KEYBOARD SHORTCUTS =====================
document.addEventListener('keydown', function(event) {
    if (event.key.toLowerCase() === 'h') document.querySelector('.hero')?.scrollIntoView({ behavior: 'smooth' });
    if (event.key.toLowerCase() === 'c') document.querySelector('#courses')?.scrollIntoView({ behavior: 'smooth' });
    if (event.key.toLowerCase() === 'f') document.querySelector('#featured')?.scrollIntoView({ behavior: 'smooth' });
    if (event.key.toLowerCase() === 't') document.querySelector('#testimonials')?.scrollIntoView({ behavior: 'smooth' });
});

console.log('✅ EduLux Script Loaded Successfully');
console.log('🎓 EduLux - منصة تعليمية احترافية');
