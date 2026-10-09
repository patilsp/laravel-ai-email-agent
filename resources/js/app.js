import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    
    // -------------------------------------------------------------
    // 1. Mobile Navigation Menu Toggle
    // -------------------------------------------------------------
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const closeIcon = document.getElementById('close-icon');
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

    if (mobileMenuBtn && mobileMenu) {
        const toggleMenu = (forceState) => {
            const isOpen = forceState !== undefined ? forceState : mobileMenu.classList.contains('hidden');
            if (isOpen) {
                mobileMenu.classList.remove('hidden');
                mobileMenu.classList.add('flex');
                mobileMenuBtn.setAttribute('aria-expanded', 'true');
                if (hamburgerIcon) hamburgerIcon.classList.add('hidden');
                if (closeIcon) closeIcon.classList.remove('hidden');
            } else {
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('flex');
                mobileMenuBtn.setAttribute('aria-expanded', 'false');
                if (hamburgerIcon) hamburgerIcon.classList.remove('hidden');
                if (closeIcon) closeIcon.classList.add('hidden');
            }
        };

        mobileMenuBtn.addEventListener('click', () => toggleMenu());
        mobileNavLinks.forEach(link => link.addEventListener('click', () => toggleMenu(false)));
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
                toggleMenu(false);
            }
        });
    }

    // -------------------------------------------------------------
    // 2. Interactive 3-Column Pro Workbench Engine
    // -------------------------------------------------------------
    const scenarios = {
        client: {
            senderName: 'Sarah Jenkins',
            senderEmail: '<sarah@enterprise.io>',
            senderTime: '10:14 AM',
            subject: 'Urgent: Q4 Production Rollout Approval & Security Audit',
            body: '"Hi team, our executive committee requires the finalized security penetration test report by 3:00 PM today to sign off on Friday\'s production deployment. Could you share the signed compliance copy?"',
            priorityText: 'Urgent (9.8 / 10)',
            priorityClass: 'bg-rose-950 text-rose-300 border-rose-800',
            categoryText: '🏷️ Enterprise Client',
            categoryClass: 'bg-blue-950 text-blue-300 border-blue-800',
            intentTag: 'Intent: Security Addendum',
            summary: 'Sarah needs the signed penetration test report prior to 3:00 PM for the executive board sign-off. High commercial impact.',
            draft: '"Hi Sarah, thanks for reaching out. I\'ve attached our completed SOC2 Type II compliance audit and the signed security report. Please let us know if any further clarifications are needed ahead of Friday\'s deployment."'
        },
        meeting: {
            senderName: 'David Chen',
            senderEmail: '<david@platformleads.co>',
            senderTime: '11:30 AM',
            subject: 'Follow-up: Architecture Sync & API v2 Migration',
            body: '"Thanks for the productive sync earlier. Could you send over the updated migration timeline and who from your team will lead the webhook refactor next week?"',
            priorityText: 'Important (7.2 / 10)',
            priorityClass: 'bg-amber-950 text-amber-300 border-amber-800',
            categoryText: '👥 Team / Architecture',
            categoryClass: 'bg-purple-950 text-purple-300 border-purple-800',
            intentTag: 'Intent: Action Item Follow-up',
            summary: 'David requests the API v2 migration timeline and assignment of lead engineer for webhook refactor.',
            draft: '"Hi David, great speaking today. The migration roadmap is scheduled for a phased rollout starting Tuesday. Liam will lead the webhook implementation on our side. I\'ll circulate the RFC document tomorrow morning."'
        },
        invoice: {
            senderName: 'Stripe Billing',
            senderEmail: '<invoices@stripe.com>',
            senderTime: '08:45 AM',
            subject: 'Invoice #INV-2026-9482 for Enterprise Plan ($1,250.00)',
            body: '"Your monthly invoice for Cloud Services is ready. Amount: $1,250.00 due on October 15, 2026. Payment will be processed automatically."',
            priorityText: 'Routine (3.0 / 10)',
            priorityClass: 'bg-stone-800 text-stone-400 border-stone-700',
            categoryText: '💳 Finance / Accounting',
            categoryClass: 'bg-emerald-950 text-emerald-300 border-emerald-800',
            intentTag: 'Intent: Receipt / Record',
            summary: 'Automated invoice receipt for $1,250.00. No immediate response required. Forwarded to accounting records.',
            draft: '"Acknowledged and forwarded to finance records for monthly reconciliation. Thank you."'
        }
    };

    let activeScenarioKey = 'client';

    const senderNameEl = document.getElementById('demo-sender-name');
    const senderEmailEl = document.getElementById('demo-sender-email');
    const senderTimeEl = document.getElementById('demo-sender-time');
    const subjectEl = document.getElementById('demo-subject');
    const bodyEl = document.getElementById('demo-body');
    const priorityBadgeEl = document.getElementById('demo-priority-badge');
    const categoryBadgeEl = document.getElementById('demo-category-badge');
    const intentTagEl = document.getElementById('demo-intent-tag');
    const summaryEl = document.getElementById('demo-summary');
    const draftTextarea = document.getElementById('demo-draft-textarea');
    const approvedOverlay = document.getElementById('demo-approved-overlay');
    const scenarioBtns = document.querySelectorAll('.demo-scenario-btn');
    const editBtn = document.getElementById('demo-edit-btn');
    const approveBtn = document.getElementById('demo-approve-btn');
    const resetBtn = document.getElementById('demo-reset-btn');

    const updateDemoView = (key) => {
        const data = scenarios[key];
        if (!data) return;
        activeScenarioKey = key;

        // Hide overlay on switch
        if (approvedOverlay) approvedOverlay.classList.add('hidden');

        // Update elements
        if (senderNameEl) senderNameEl.textContent = data.senderName;
        if (senderEmailEl) senderEmailEl.textContent = data.senderEmail;
        if (senderTimeEl) senderTimeEl.textContent = data.senderTime;
        if (subjectEl) subjectEl.textContent = data.subject;
        if (bodyEl) bodyEl.textContent = data.body;
        
        if (priorityBadgeEl) {
            priorityBadgeEl.textContent = data.priorityText;
            priorityBadgeEl.className = `px-2 py-0.5 rounded font-bold uppercase text-[10px] border ${data.priorityClass}`;
        }
        
        if (categoryBadgeEl) {
            categoryBadgeEl.textContent = data.categoryText;
            categoryBadgeEl.className = `px-2 py-0.5 rounded text-[10px] font-semibold border ${data.categoryClass}`;
        }

        if (intentTagEl) intentTagEl.textContent = data.intentTag;
        if (summaryEl) summaryEl.textContent = data.summary;
        if (draftTextarea) draftTextarea.value = data.draft;

        // Update active styling
        scenarioBtns.forEach(btn => {
            const btnKey = btn.getAttribute('data-scenario');
            if (btnKey === key) {
                btn.className = 'demo-scenario-btn w-full text-left p-3 rounded-lg border transition-all space-y-1 group bg-[#1e232c] border-blue-500/80 text-white';
            } else {
                btn.className = 'demo-scenario-btn w-full text-left p-3 rounded-lg border border-stone-800 hover:border-stone-700 bg-transparent hover:bg-[#1a1e26] text-stone-300 transition-all space-y-1 group';
            }
        });
    };

    scenarioBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const key = btn.getAttribute('data-scenario');
            updateDemoView(key);
        });
    });

    if (editBtn && draftTextarea) {
        editBtn.addEventListener('click', () => {
            if (approvedOverlay) approvedOverlay.classList.add('hidden');
            draftTextarea.focus();
            draftTextarea.select();
        });
    }

    if (approveBtn && approvedOverlay) {
        approveBtn.addEventListener('click', () => {
            approvedOverlay.classList.remove('hidden');
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            updateDemoView(activeScenarioKey);
        });
    }

    // -------------------------------------------------------------
    // 3. FAQ Accordion
    // -------------------------------------------------------------
    const faqToggles = document.querySelectorAll('.faq-toggle');
    faqToggles.forEach(toggle => {
        toggle.addEventListener('click', () => {
            const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
            const content = toggle.nextElementSibling;
            const icon = toggle.querySelector('.faq-icon');

            // Close all others
            faqToggles.forEach(otherToggle => {
                if (otherToggle !== toggle) {
                    otherToggle.setAttribute('aria-expanded', 'false');
                    if (otherToggle.nextElementSibling) otherToggle.nextElementSibling.classList.add('hidden');
                    const otherIcon = otherToggle.querySelector('.faq-icon');
                    if (otherIcon) otherIcon.textContent = '[+]';
                }
            });

            // Toggle selected
            if (isExpanded) {
                toggle.setAttribute('aria-expanded', 'false');
                if (content) content.classList.add('hidden');
                if (icon) icon.textContent = '[+]';
            } else {
                toggle.setAttribute('aria-expanded', 'true');
                if (content) content.classList.remove('hidden');
                if (icon) icon.textContent = '[-]';
            }
        });
    });

    // -------------------------------------------------------------
    // 4. Early Access Form Handler
    // -------------------------------------------------------------
    const earlyAccessForm = document.getElementById('early-access-form');
    const earlyAccessSuccess = document.getElementById('early-access-success');
    const earlyAccessSubmit = document.getElementById('early-access-submit');
    const earlyAccessEmail = document.getElementById('early-access-email');

    if (earlyAccessForm && earlyAccessSuccess) {
        earlyAccessForm.addEventListener('submit', (e) => {
            e.preventDefault();
            if (earlyAccessEmail && earlyAccessEmail.value) {
                if (earlyAccessSubmit) {
                    earlyAccessSubmit.disabled = true;
                    earlyAccessSubmit.innerHTML = `<span>Enrolling...</span>`;
                }
                setTimeout(() => {
                    earlyAccessForm.classList.add('hidden');
                    earlyAccessSuccess.classList.remove('hidden');
                }, 500);
            }
        });
    }
});
