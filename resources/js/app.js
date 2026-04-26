/* ──────────────────────────────────────────────────────────
   DeliverEats – Frontend Application JavaScript
   ────────────────────────────────────────────────────────── */

// ── Cart Management (localStorage) ────────────────────────
const Cart = {
    KEY: 'delivereats_cart',

    getAll() {
        try {
            return JSON.parse(localStorage.getItem(this.KEY)) || [];
        } catch { return []; }
    },

    save(items) {
        localStorage.setItem(this.KEY, JSON.stringify(items));
        this.updateUI();
        this.dispatchEvent();
    },

    add(item) {
        const items = this.getAll();
        const idx = items.findIndex(i => i.id === item.id && i.variantId === item.variantId);
        if (idx > -1) {
            items[idx].qty += item.qty || 1;
        } else {
            items.push({ ...item, qty: item.qty || 1 });
        }
        this.save(items);
        Toast.show('Added to cart', `${item.name} × ${item.qty || 1}`, 'success');
    },

    remove(id, variantId) {
        const items = this.getAll().filter(i => !(i.id === id && i.variantId === variantId));
        this.save(items);
    },

    updateQty(id, variantId, qty) {
        const items = this.getAll();
        const idx = items.findIndex(i => i.id === id && i.variantId === variantId);
        if (idx > -1) {
            if (qty <= 0) {
                items.splice(idx, 1);
            } else {
                items[idx].qty = qty;
            }
        }
        this.save(items);
    },

    clear() {
        localStorage.removeItem(this.KEY);
        this.updateUI();
        this.dispatchEvent();
    },

    getCount() {
        return this.getAll().reduce((sum, i) => sum + i.qty, 0);
    },

    getSubtotal() {
        return this.getAll().reduce((sum, i) => sum + (i.price * i.qty), 0);
    },

    updateUI() {
        document.querySelectorAll('[data-cart-count]').forEach(el => {
            const count = this.getCount();
            el.textContent = count;
            el.closest('.cart-badge-wrapper')?.classList.toggle('hidden', count === 0);
        });
        document.querySelectorAll('[data-cart-subtotal]').forEach(el => {
            el.textContent = this.getSubtotal().toFixed(2);
        });
    },

    dispatchEvent() {
        window.dispatchEvent(new CustomEvent('cart:updated', { detail: { items: this.getAll() } }));
    }
};

// ── Toast Notifications ───────────────────────────────────
const Toast = {
    container: null,

    init() {
        if (this.container) return;
        this.container = document.createElement('div');
        this.container.className = 'toast-container';
        document.body.appendChild(this.container);
    },

    show(title, message, type = 'info', duration = 3500) {
        this.init();
        const icons = {
            success: '<svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
            error: '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
            info: '<svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            warning: '<svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>'
        };
        const toast = document.createElement('div');
        toast.className = 'toast glass rounded-xl px-5 py-4 shadow-lg flex items-start gap-3 min-w-[320px] max-w-[420px]';
        toast.innerHTML = `
            ${icons[type] || icons.info}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-surface-900">${title}</p>
                ${message ? `<p class="text-xs text-surface-300 mt-0.5">${message}</p>` : ''}
            </div>
            <button onclick="this.closest('.toast').remove()" class="text-surface-300 hover:text-surface-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>`;
        this.container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(40px)';
            toast.style.transition = 'all 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }
};

// ── Mobile Menu Toggle ────────────────────────────────────
function initMobileMenu() {
    const toggle = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('mobile-menu');
    const overlay = document.getElementById('mobile-menu-overlay');
    if (!toggle || !menu) return;

    toggle.addEventListener('click', () => {
        menu.classList.toggle('-translate-x-full');
        overlay?.classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    });
    overlay?.addEventListener('click', () => {
        menu.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    });
}

// ── Dropdown Menus ────────────────────────────────────────
function initDropdowns() {
    document.querySelectorAll('[data-dropdown-toggle]').forEach(btn => {
        const target = document.getElementById(btn.dataset.dropdownToggle);
        if (!target) return;
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            document.querySelectorAll('.dropdown-menu.open').forEach(d => {
                if (d !== target) d.classList.remove('open');
            });
            target.classList.toggle('open');
        });
    });
    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-menu.open').forEach(d => d.classList.remove('open'));
    });
}

// ── Tabs ──────────────────────────────────────────────────
function initTabs() {
    document.querySelectorAll('[data-tabs]').forEach(tabGroup => {
        const buttons = tabGroup.querySelectorAll('[data-tab]');
        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.dataset.tab;
                buttons.forEach(b => b.classList.remove('active', 'border-brand-500', 'text-brand-600'));
                buttons.forEach(b => b.classList.add('border-transparent', 'text-gray-500'));
                btn.classList.add('active', 'border-brand-500', 'text-brand-600');
                btn.classList.remove('border-transparent', 'text-gray-500');

                const container = btn.closest('[data-tabs]').parentElement;
                container.querySelectorAll('[data-tab-panel]').forEach(panel => {
                    panel.classList.toggle('hidden', panel.dataset.tabPanel !== target);
                });
            });
        });
    });
}

// ── Modals ────────────────────────────────────────────────
function openModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.remove('hidden');
    modal.querySelector('.modal-backdrop')?.classList.remove('opacity-0');
    modal.querySelector('.modal-content')?.classList.remove('scale-95', 'opacity-0');
    document.body.classList.add('overflow-hidden');
}
function closeModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.querySelector('.modal-backdrop')?.classList.add('opacity-0');
    modal.querySelector('.modal-content')?.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }, 200);
}
function initModals() {
    document.querySelectorAll('[data-modal-open]').forEach(btn => {
        btn.addEventListener('click', () => openModal(btn.dataset.modalOpen));
    });
    document.querySelectorAll('[data-modal-close]').forEach(btn => {
        btn.addEventListener('click', () => closeModal(btn.closest('[id^="modal-"]')?.id));
    });
}

// ── Quantity Steppers ─────────────────────────────────────
function initQtySteppers() {
    document.querySelectorAll('.qty-stepper').forEach(stepper => {
        const input = stepper.querySelector('input, .qty-display');
        const minusBtn = stepper.querySelector('[data-qty-minus]');
        const plusBtn = stepper.querySelector('[data-qty-plus]');
        if (!input) return;

        minusBtn?.addEventListener('click', () => {
            let val = parseInt(input.value || input.textContent) || 1;
            if (val > 1) {
                val--;
                if (input.tagName === 'INPUT') input.value = val;
                else input.textContent = val;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
        plusBtn?.addEventListener('click', () => {
            let val = parseInt(input.value || input.textContent) || 0;
            val++;
            if (input.tagName === 'INPUT') input.value = val;
            else input.textContent = val;
            input.dispatchEvent(new Event('change', { bubbles: true }));
        });
    });
}

// ── Smooth counter animation ──────────────────────────────
function animateCounters() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            const target = parseFloat(el.dataset.countTo);
            const decimals = (el.dataset.decimals) ? parseInt(el.dataset.decimals) : 0;
            const duration = parseInt(el.dataset.duration) || 1200;
            const start = performance.now();

            function tick(now) {
                const elapsed = now - start;
                const progress = Math.min(elapsed / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = (target * eased).toFixed(decimals);
                if (progress < 1) requestAnimationFrame(tick);
                else el.textContent = target.toFixed(decimals);
            }
            requestAnimationFrame(tick);
            observer.unobserve(el);
        });
    }, { threshold: 0.3 });

    document.querySelectorAll('[data-count-to]').forEach(el => observer.observe(el));
}

// ── Sidebar collapse ─────────────────────────────────────
function initSidebar() {
    const toggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    if (!toggle || !sidebar) return;
    toggle.addEventListener('click', () => {
        sidebar.classList.toggle('w-64');
        sidebar.classList.toggle('w-20');
        sidebar.querySelectorAll('.sidebar-label').forEach(l => l.classList.toggle('hidden'));
    });
}

// ── Search with debounce ──────────────────────────────────
function initSearch() {
    const searchInputs = document.querySelectorAll('[data-search]');
    searchInputs.forEach(input => {
        let timer;
        input.addEventListener('input', (e) => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                const query = e.target.value.toLowerCase().trim();
                const targetSelector = input.dataset.search;
                document.querySelectorAll(targetSelector).forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(query) ? '' : 'none';
                });
            }, 250);
        });
    });
}

// ── Star Rating Interactive ───────────────────────────────
function initStarRatings() {
    document.querySelectorAll('.star-rating-input').forEach(container => {
        const stars = container.querySelectorAll('.star');
        const input = container.querySelector('input[type="hidden"]');
        stars.forEach((star, idx) => {
            star.addEventListener('mouseenter', () => {
                stars.forEach((s, i) => {
                    s.classList.toggle('filled', i <= idx);
                    s.classList.toggle('empty', i > idx);
                });
            });
            star.addEventListener('click', () => {
                if (input) input.value = idx + 1;
                stars.forEach((s, i) => {
                    s.dataset.selected = i <= idx ? '1' : '0';
                    s.classList.toggle('filled', i <= idx);
                    s.classList.toggle('empty', i > idx);
                });
            });
        });
        container.addEventListener('mouseleave', () => {
            stars.forEach(s => {
                s.classList.toggle('filled', s.dataset.selected === '1');
                s.classList.toggle('empty', s.dataset.selected !== '1');
            });
        });
    });
}

// ── Initialize Everything ─────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    Cart.updateUI();
    initMobileMenu();
    initDropdowns();
    initTabs();
    initModals();
    initQtySteppers();
    animateCounters();
    initSidebar();
    initSearch();
    initStarRatings();
});

// Expose globally
window.Cart = Cart;
window.Toast = Toast;
window.openModal = openModal;
window.closeModal = closeModal;
