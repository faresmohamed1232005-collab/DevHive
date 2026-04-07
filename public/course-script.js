// ===================== ANIMATIONS CSS =====================
const animStyle = document.createElement('style');
animStyle.textContent = `
    @keyframes slideInToast {
        from { transform: translateX(400px); opacity: 0; }
        to   { transform: translateX(0);     opacity: 1; }
    }
    @keyframes slideOutToast {
        from { transform: translateX(0);     opacity: 1; }
        to   { transform: translateX(400px); opacity: 0; }
    }
`;
document.head.appendChild(animStyle);

// ===================== TOAST NOTIFICATION =====================
function showToast(message, type = 'info') {
    const existingToast = document.querySelector('.toast');
    if (existingToast) existingToast.remove();

    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;

    const bgColor = type === 'success' ? '#4ade80'
                  : type === 'error'   ? '#ef4444'
                  : '#3b82f6';

    toast.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: ${bgColor};
        color: white;
        padding: 1.2rem 2rem;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(42, 22, 84, 0.6);
        z-index: 10000;
        font-weight: 600;
        animation: slideInToast 0.4s ease-out;
    `;

    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.animation = 'slideOutToast 0.4s ease-out';
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}

// ===================== كل الكود بيتشغل بعد تحميل الصفحة =====================
document.addEventListener('DOMContentLoaded', function () {

    // ===================== HAMBURGER MENU =====================
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');

    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', function (e) {
            e.stopPropagation();
            hamburger.classList.toggle('active');
            mobileMenu.classList.toggle('active');
        });
        document.addEventListener('click', function (e) {
            if (!e.target.closest('header') && mobileMenu.classList.contains('active')) {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('active');
            }
        });
    }

    // ===================== HEADER SCROLL EFFECT =====================
    const header = document.getElementById('header');
    window.addEventListener('scroll', () => {
        if (header) header.classList.toggle('scrolled', window.scrollY > 50);
    });

    // ===================== LOAD USER DATA =====================
    // التحقق إن اليوزر مسجل — لو مش مسجل يرجع للرئيسية
    const booked = localStorage.getItem('edulux_booked') || localStorage.getItem('isBooked');
    if (!booked || booked !== 'true') {
        window.location.href = '/index.html';
        return;
    }

    // ملء بيانات المستخدم في الصفحة
    const raw = localStorage.getItem('edulux_user') || localStorage.getItem('userBookingData');
    if (raw) {
        try {
            const userData = JSON.parse(raw);
            const nameEl     = document.getElementById('userName');
            const emailEl    = document.getElementById('userEmail');
            const usernameEl = document.getElementById('userUsername');
            if (nameEl)     nameEl.textContent    = userData.name     || userData.fullName || '';
            if (emailEl)    emailEl.textContent    = userData.email    || '';
            if (usernameEl) usernameEl.textContent = userData.username || '';
        } catch (e) {}
    }

    // ===================== TABS =====================
    function switchTab(tabName) {
        // إخفاء كل الـ tabs وإزالة active من الأزرار
        document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('[data-switch]').forEach(l => l.classList.remove('active'));

        // إظهار الـ tab المطلوب
        const tab = document.getElementById(tabName + 'Tab');
        if (tab) tab.classList.add('active');

        // تفعيل الزرار المناسب لو موجود
        const btn = document.querySelector('.tab-btn[data-tab="' + tabName + '"]');
        if (btn) btn.classList.add('active');

        // تفعيل الـ nav link المناسب
        const navLink = document.querySelector('[data-switch="' + tabName + '"]');
        if (navLink) navLink.classList.add('active');

        // إغلاق المنيو لو مفتوح
        if (hamburger) hamburger.classList.remove('active');
        if (mobileMenu) mobileMenu.classList.remove('active');

        // scroll للمحتوى
        const mainContent = document.querySelector('.course-main');
        if (mainContent) mainContent.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // ربط لينكات الـ nav بالـ tabs
    document.querySelectorAll('[data-switch]').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            switchTab(this.getAttribute('data-switch'));
        });
    });

    // ===================== CONTACT LINK =====================
    function scrollToFooter() {
        const footer = document.getElementById('courseFooter');
        if (footer) footer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    const contactLink = document.getElementById('contactLink');
    const mobileContactLink = document.getElementById('mobileContactLink');
    if (contactLink) contactLink.addEventListener('click', function(e) { e.preventDefault(); scrollToFooter(); });
    if (mobileContactLink) mobileContactLink.addEventListener('click', function(e) { e.preventDefault(); scrollToFooter(); });

    // ===================== LOGOUT =====================
    function logout() {
        // مسح كل بيانات اليوزر والحجز
        localStorage.removeItem('userBookingData');
        localStorage.removeItem('isBooked');
        localStorage.removeItem('edulux_user');
        localStorage.removeItem('edulux_booked');

        showToast('تم تسجيل الخروج بنجاح', 'success');

        // بعد ثانية يروح للصفحة الرئيسية
        // الزرار هيرجع "احجز مقعدك الان" تلقائياً لأننا مسحنا isBooked
        setTimeout(() => { window.location.href = '/index.html'; }, 1000);
    }

    const logoutBtn = document.getElementById('logoutBtn');
    const mobileLogoutBtn = document.getElementById('mobileLogoutBtn');
    if (logoutBtn) logoutBtn.addEventListener('click', logout);
    if (mobileLogoutBtn) mobileLogoutBtn.addEventListener('click', logout);

    // ===================== HOME BUTTON =====================
    const homeBtn = document.getElementById('homeBtn');
    if (homeBtn) homeBtn.addEventListener('click', function() {
        // الرجوع للصفحة الرئيسية بدون مسح البيانات
        window.location.href = '/index.html';
    });

    // ===================== VIDEO PLAYER =====================
    const videoModal  = document.getElementById('videoModal');
    const videoPlayer = document.getElementById('videoPlayer');
    const videoTitle  = document.getElementById('videoTitle');

    function playLesson(title, videoUrl) {
        if (!videoModal || !videoPlayer) return;
        if (videoTitle) videoTitle.textContent = title;
        videoPlayer.src = videoUrl || 'https://www.w3schools.com/html/mov_bbb.mp4';
        videoModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeVideo() {
        if (!videoModal || !videoPlayer) return;
        videoModal.style.display = 'none';
        videoPlayer.pause();
        videoPlayer.src = '';
        document.body.style.overflow = 'auto';
    }

    // ربط أزرار التشغيل
    document.querySelectorAll('.btn-play').forEach(btn => {
        btn.addEventListener('click', function () {
            const lessonItem = this.closest('.lesson-item');
            const title = lessonItem?.querySelector('h3')?.textContent || 'الدرس';
            playLesson(title);
        });
    });

    const closeVideoBtn = document.querySelector('#videoModal .modal-close');
    if (closeVideoBtn) closeVideoBtn.addEventListener('click', closeVideo);
    if (videoModal) videoModal.addEventListener('click', function(e) { if (e.target === videoModal) closeVideo(); });
    window.closeVideo = closeVideo;

    // ===================== UPLOAD ASSIGNMENT MODAL =====================
    const uploadModal = document.getElementById('uploadModal');
    const uploadForm  = document.getElementById('uploadForm');

    function openUploadModal() {
        if (uploadModal) {
            uploadModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeUploadModal() {
        if (uploadModal) {
            uploadModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    // ربط أزرار رفع الواجب
    document.querySelectorAll('.btn-submit').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            openUploadModal();
        });
    });

    const closeUploadBtn = document.querySelector('#uploadModal .modal-close');
    if (closeUploadBtn) closeUploadBtn.addEventListener('click', closeUploadModal);
    if (uploadModal) uploadModal.addEventListener('click', function(e) { if (e.target === uploadModal) closeUploadModal(); });

    // تأكيد رفع الواجب
    const submitBtn = document.querySelector('#uploadModal .btn-next');
    if (submitBtn) {
        submitBtn.addEventListener('click', function() {
            const assignmentSelect = document.getElementById('assignmentSelect');
            const fileInput        = document.getElementById('fileInput');
            if (!assignmentSelect?.value || !fileInput?.files.length) {
                showToast('الرجاء اختيار الواجب والملف', 'error');
                return;
            }
            showToast('تم رفع الملف بنجاح! ✓', 'success');
            if (uploadForm) uploadForm.reset();
            setTimeout(closeUploadModal, 1500);
        });
    }

    window.closeUploadModal = closeUploadModal;

    // ===================== BTN-DOWNLOAD =====================
    document.querySelectorAll('.btn-download').forEach(btn => {
        btn.addEventListener('click', function () {
            showToast('جاري تحميل الملف...', 'info');
        });
    });

    console.log('✅ Course Page Loaded Successfully');
});
