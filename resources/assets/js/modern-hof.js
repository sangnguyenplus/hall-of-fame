/**
 * Hall of Fame - Modern 2025 JavaScript Enhancements
 * Provides smooth animations, interactions, and modern UX
 */

class HallOfFameUI {
    constructor() {
        this.init();
    }

    init() {
        this.setupAnimations();
        this.setupInteractions();
        this.setupFormEnhancements();
        this.setupNotifications();
    }

    // Modern Animation System
    setupAnimations() {
        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('hof-animate-fade-in');
                }
            });
        }, observerOptions);

        // Observe all cards and stat elements
        document.querySelectorAll('.hof-card, .hof-stat-card').forEach(el => {
            observer.observe(el);
        });

        // Stagger animations for multiple elements
        document.querySelectorAll('.hof-animate-slide-in').forEach((el, index) => {
            el.style.animationDelay = `${index * 0.1}s`;
        });
    }

    // Enhanced Interactions
    setupInteractions() {
        // Enhanced button hover effects
        document.querySelectorAll('.hof-btn').forEach(btn => {
            btn.addEventListener('mouseenter', this.addButtonGlow);
            btn.addEventListener('mouseleave', this.removeButtonGlow);
        });

        // Card hover effects
        document.querySelectorAll('.hof-card').forEach(card => {
            card.addEventListener('mouseenter', this.enhanceCardHover);
            card.addEventListener('mouseleave', this.removeCardHover);
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', this.smoothScroll);
        });

        // Enhanced nav link interactions
        document.querySelectorAll('.hof-nav-link').forEach(link => {
            link.addEventListener('click', this.handleNavClick);
        });
    }

    // Form Enhancements
    setupFormEnhancements() {
        // Floating labels
        document.querySelectorAll('.hof-form-control').forEach(input => {
            input.addEventListener('focus', this.handleInputFocus);
            input.addEventListener('blur', this.handleInputBlur);
            input.addEventListener('input', this.handleInputChange);
        });

        // Real-time validation
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', this.handleFormSubmit);
        });

        // Auto-resize textareas
        document.querySelectorAll('textarea.hof-form-control').forEach(textarea => {
            textarea.addEventListener('input', this.autoResizeTextarea);
        });
    }

    // Notification System
    setupNotifications() {
        this.createNotificationContainer();
    }

    // Button Effects
    addButtonGlow(e) {
        const btn = e.target.closest('.hof-btn');
        if (btn.classList.contains('hof-btn-primary')) {
            btn.style.boxShadow = '0 10px 25px rgba(99, 102, 241, 0.4), 0 0 20px rgba(99, 102, 241, 0.3)';
        }
    }

    removeButtonGlow(e) {
        const btn = e.target.closest('.hof-btn');
        btn.style.boxShadow = '';
    }

    // Card Effects
    enhanceCardHover(e) {
        const card = e.target.closest('.hof-card');
        card.style.transform = 'translateY(-8px) scale(1.02)';
        card.style.boxShadow = '0 25px 50px -12px rgba(0, 0, 0, 0.25)';
    }

    removeCardHover(e) {
        const card = e.target.closest('.hof-card');
        card.style.transform = '';
        card.style.boxShadow = '';
    }

    // Smooth Scrolling
    smoothScroll(e) {
        e.preventDefault();
        const target = document.querySelector(e.target.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }

    // Navigation
    handleNavClick(e) {
        // Remove active class from all nav links
        document.querySelectorAll('.hof-nav-link').forEach(link => {
            link.classList.remove('active');
        });

        // Add active class to clicked link
        e.target.closest('.hof-nav-link').classList.add('active');

        // Add ripple effect
        this.createRipple(e);
    }

    // Form Interactions
    handleInputFocus(e) {
        const input = e.target;
        const group = input.closest('.hof-form-group');
        group.classList.add('focused');

        // Add floating label effect
        const label = group.querySelector('.hof-form-label');
        if (label) {
            label.style.transform = 'translateY(-8px) scale(0.85)';
            label.style.color = 'var(--hof-primary)';
        }
    }

    handleInputBlur(e) {
        const input = e.target;
        const group = input.closest('.hof-form-group');
        group.classList.remove('focused');

        const label = group.querySelector('.hof-form-label');
        if (label && !input.value) {
            label.style.transform = '';
            label.style.color = '';
        }
    }

    handleInputChange(e) {
        const input = e.target;

        // Real-time validation
        this.validateInput(input);

        // Auto-save draft (for textareas)
        if (input.tagName === 'TEXTAREA') {
            this.saveDraft(input);
        }
    }

    autoResizeTextarea(e) {
        const textarea = e.target;
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }

    validateInput(input) {
        const value = input.value.trim();
        const type = input.type;
        const group = input.closest('.hof-form-group');

        // Remove existing validation classes
        input.classList.remove('is-valid', 'is-invalid');

        let isValid = true;

        if (input.required && !value) {
            isValid = false;
        } else if (type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            isValid = emailRegex.test(value);
        } else if (type === 'url' && value) {
            try {
                new URL(value);
            } catch {
                isValid = false;
            }
        }

        input.classList.add(isValid ? 'is-valid' : 'is-invalid');
    }

    handleFormSubmit(e) {
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');

        if (submitBtn) {
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;

            // Add loading animation
            const originalText = submitBtn.textContent;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

            // Reset after 3 seconds if form doesn't redirect
            setTimeout(() => {
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }, 3000);
        }
    }

    // Ripple Effect
    createRipple(e) {
        const button = e.target.closest('.hof-btn, .hof-nav-link');
        const rect = button.getBoundingClientRect();
        const ripple = document.createElement('span');
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s ease-out;
            pointer-events: none;
        `;

        button.style.position = 'relative';
        button.style.overflow = 'hidden';
        button.appendChild(ripple);

        setTimeout(() => ripple.remove(), 600);
    }

    // Notification System
    createNotificationContainer() {
        if (!document.querySelector('.hof-notifications')) {
            const container = document.createElement('div');
            container.className = 'hof-notifications';
            container.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                max-width: 400px;
            `;
            document.body.appendChild(container);
        }
    }

    showNotification(message, type = 'info', duration = 5000) {
        const container = document.querySelector('.hof-notifications');
        const notification = document.createElement('div');

        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };

        notification.className = `hof-notification hof-notification-${type}`;
        notification.innerHTML = `
            <div class="hof-notification-content">
                <i class="${icons[type]} me-2"></i>
                <span>${message}</span>
                <button class="hof-notification-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        notification.style.cssText = `
            background: white;
            border-left: 4px solid var(--hof-${type});
            border-radius: var(--hof-radius-lg);
            box-shadow: var(--hof-shadow-lg);
            margin-bottom: 10px;
            transform: translateX(100%);
            transition: var(--hof-transition-normal);
            opacity: 0;
        `;

        container.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
            notification.style.opacity = '1';
        }, 100);

        // Auto remove
        if (duration > 0) {
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, duration);
        }
    }

    // Draft saving
    saveDraft(textarea) {
        const key = `hof_draft_${textarea.name || textarea.id}`;
        localStorage.setItem(key, textarea.value);
    }

    loadDraft(textarea) {
        const key = `hof_draft_${textarea.name || textarea.id}`;
        const draft = localStorage.getItem(key);
        if (draft && !textarea.value) {
            textarea.value = draft;
            this.autoResizeTextarea({ target: textarea });
        }
    }

    // Utility: Debounce function
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .hof-notification-content {
        display: flex;
        align-items: center;
        padding: 1rem;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .hof-notification-close {
        background: none;
        border: none;
        color: var(--hof-gray-500);
        cursor: pointer;
        margin-left: auto;
        padding: 0;
        font-size: 0.75rem;
    }
    
    .hof-notification-close:hover {
        color: var(--hof-gray-700);
    }
    
    .hof-form-control.is-valid {
        border-color: var(--hof-success);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    
    .hof-form-control.is-invalid {
        border-color: var(--hof-danger);
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
`;
document.head.appendChild(style);

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.hofUI = new HallOfFameUI();

    // Load drafts for textareas
    document.querySelectorAll('textarea.hof-form-control').forEach(textarea => {
        window.hofUI.loadDraft(textarea);
    });
});

// Global notification function
window.showNotification = (message, type, duration) => {
    if (window.hofUI) {
        window.hofUI.showNotification(message, type, duration);
    }
};
