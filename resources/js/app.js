import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {

    // =============================================================
    // 1. Vaultline Floating Cyan Particles Generator
    // =============================================================
    const pWrap = document.getElementById('particles');
    if (pWrap) {
        pWrap.style.cssText = 'position:fixed; inset:0; z-index:0; pointer-events:none; overflow:hidden;';
        for (let i = 0; i < 28; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + 'vw';
            p.style.top = Math.random() * 100 + 'vh';
            p.style.animationDuration = (10 + Math.random() * 10) + 's';
            p.style.animationDelay = (Math.random() * 6) + 's';
            pWrap.appendChild(p);
        }
    }

    // =============================================================
    // 2. Ambient Parallax 3D Floor Grid
    // =============================================================
    const floor = document.querySelector('.floor');
    if (floor) {
        document.addEventListener('mousemove', (e) => {
            const nx = (e.clientX / window.innerWidth - 0.5);
            floor.style.transform = `rotateX(75deg) translateX(${nx * 18}px)`;
        });
    }

    // =============================================================
    // 3. 3D Tilt Card Interaction (Vaultline Cyber Cards)
    // =============================================================
    const cards3D = document.querySelectorAll('.role-card, .cyber-card-3d');
    cards3D.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const r = card.getBoundingClientRect();
            const px = (e.clientX - r.left) / r.width;
            const py = (e.clientY - r.top) / r.height;
            const rx = (0.5 - py) * 10;
            const ry = (px - 0.5) * 12;
            card.style.transform = `rotateX(${rx}deg) rotateY(${ry}deg) scale(1.015)`;
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'rotateX(0deg) rotateY(0deg) scale(1)';
        });
    });

    // =============================================================
    // 4. Staggered Entrance Animations
    // =============================================================
    const animateIn = (el, delay) => {
        if (!el) return;
        setTimeout(() => {
            el.style.transition = 'opacity .6s cubic-bezier(.2,.8,.2,1), transform .6s cubic-bezier(.2,.8,.2,1)';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, delay);
    };

    ['eyebrow', 'hero-title', 'hero-sub', 'hero-search-bar', 'hero-badge-bar'].forEach((id, i) => {
        const el = document.getElementById(id);
        if (el) animateIn(el, 80 + i * 100);
    });

    // =============================================================
    // 5. Global Toast Notification System
    // =============================================================
    const toast = document.getElementById('toast');
    let toastTimer = null;
    window.showToast = (message = 'Copied to clipboard') => {
        if (!toast) return;
        toast.textContent = message;
        toast.classList.add('show');
        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toast.classList.remove('show');
        }, 2200);
    };

    // Global copy button handler
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.closest('.cred-line')?.querySelector('.cred-val') || 
                           btn.closest('[data-copy-val]') || 
                           btn;
            const value = target.dataset?.real || target.dataset?.copyVal || target.textContent.trim();
            if (value && navigator.clipboard) {
                navigator.clipboard.writeText(value).then(() => {
                    showToast('Copied to clipboard');
                }).catch(() => {
                    showToast('Copied: ' + value.substring(0, 20));
                });
            }
        });
    });

    // =============================================================
    // 6. Laser Scan Line Trigger
    // =============================================================
    window.triggerScanLine = (container) => {
        if (!container) return;
        const scan = document.createElement('div');
        scan.className = 'scan-line run';
        container.appendChild(scan);
        scan.addEventListener('animationend', () => scan.remove());
    };

    // =============================================================
    // 7. Mobile Navigation Drawer Toggle
    // =============================================================
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
                if (hamburgerIcon) hamburgerIcon.classList.add('hidden');
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

    // =============================================================
    // 8. Milo Interactive AI Studio & Dynamic Tone Morpher
    // =============================================================
    const scenarios = {
        client: {
            senderName: 'Sarah Jenkins',
            senderEmail: 'sarah@enterprise.io',
            senderRole: 'VP of Enterprise Security',
            senderTime: '10:14 AM',
            subject: 'Urgent: Q4 Production Rollout Approval & SOC2 Audit Report',
            body: '"Hi Alex, our executive compliance committee requires the finalized security penetration test report by 3:00 PM today to sign off on Friday\'s production deployment. Could you please share the signed compliance copy?"',
            priorityScore: '9.8',
            priorityTier: 'Urgent',
            priorityChipClass: 'red',
            categoryTag: '🏷️ Enterprise Client',
            intent: 'Security Report & Deadline Sign-off',
            sentiment: 'Polite & Time-Sensitive',
            deadlineText: 'Today at 3:00 PM EST',
            summary: 'Sarah needs the signed SOC2 compliance report and pen-test by 3:00 PM for the board sign-off before Friday deployment.',
            tones: {
                executive: 'Hi Sarah,\n\nThanks for following up. I\'ve attached our finalized SOC2 Type II compliance audit and the signed security penetration report.\n\nPlease let me know if the committee needs any additional clarification ahead of Friday\'s rollout.\n\nBest regards,\nAlex',
                concise: 'Hi Sarah,\n\nAttached is the signed SOC2 Type II report and pen-test audit for the committee. All prerequisites for Friday\'s deployment are cleared on our side.\n\nBest,\nAlex',
                empathetic: 'Hi Sarah,\n\nThanks so much for checking in! We completely understand the importance of the 3 PM deadline. Attached you\'ll find the full SOC2 audit and signed test report. Wishing you a smooth review with the committee!\n\nWarmly,\nAlex',
                bullet: 'Hi Sarah,\n\n• Attached: SOC2 Type II Compliance Report & Signed Pen-Test\n• Status: Ready for 3 PM committee review\n• Rollout: Friday production deployment is on schedule\n\nBest regards,\nAlex'
            }
        },
        meeting: {
            senderName: 'David Chen',
            senderEmail: 'david@platformleads.co',
            senderRole: 'Lead Cloud Architect',
            senderTime: '11:30 AM',
            subject: 'Follow-up: Architecture Sync & API v2 Migration Timeline',
            body: '"Thanks for the productive sync earlier. Could you send over the updated migration timeline and confirm who from your team will lead the webhook refactor next week?"',
            priorityScore: '7.4',
            priorityTier: 'Important',
            priorityChipClass: 'amber',
            categoryTag: '⚡ Engineering / DevOps',
            intent: 'Technical Roadmap & Resource Assignment',
            sentiment: 'Constructive & Decisive',
            deadlineText: 'Next Sprint (Tuesday)',
            summary: 'David requests the API v2 migration schedule and the primary engineering contact for the webhook refactor.',
            tones: {
                executive: 'Hi David,\n\nGreat speaking today. The API v2 migration roadmap is scheduled for a phased rollout starting next Tuesday. Liam Vance will lead the webhook implementation on our side. I\'ll circulate the RFC document tomorrow morning.\n\nBest regards,\nAlex',
                concise: 'Hi David,\n\nMigration timeline starts Tuesday. Liam will lead our webhook refactor. RFC will be in your inbox tomorrow morning.\n\nBest,\nAlex',
                empathetic: 'Hi David,\n\nReally appreciated our conversation today! We\'re excited to push the API v2 forward. The phased rollout is set for Tuesday, and Liam is eager to spearhead the webhooks. Talk soon!\n\nBest,\nAlex',
                bullet: 'Hi David,\n\n1. Migration Schedule: Phase 1 starts Tuesday\n2. Lead Engineer: Liam Vance (Webhooks & API v2)\n3. Next Steps: RFC documentation circulating tomorrow morning\n\nThanks,\nAlex'
            }
        },
        invoice: {
            senderName: 'Stripe Billing',
            senderEmail: 'invoices@stripe.com',
            senderRole: 'Automated Financial Stream',
            senderTime: '08:45 AM',
            subject: 'Invoice #INV-2026-9482 for Enterprise Plan ($1,250.00)',
            body: '"Your monthly invoice for Cloud Infrastructure & API compute is ready. Amount: $1,250.00 due on October 15, 2026. Payment will be processed automatically using your default payment method."',
            priorityScore: '3.1',
            priorityTier: 'Routine',
            priorityChipClass: '',
            categoryTag: '💳 Finance / Accounting',
            intent: 'Monthly Billing Notice',
            sentiment: 'Automated Transactional',
            deadlineText: 'Auto-processed Oct 15',
            summary: 'Automated invoice receipt for $1,250.00. No immediate reply needed. Forwarded to financial ledger records.',
            tones: {
                executive: 'Acknowledged. Invoice #INV-2026-9482 for $1,250.00 has been verified and forwarded to our finance department for monthly accounting reconciliation.',
                concise: 'Invoice verified and logged into accounting archives. Payment will process automatically.',
                empathetic: 'Thank you for sending over the monthly invoice breakdown. Logged and approved for accounting records.',
                bullet: '• Invoice ID: #INV-2026-9482\n• Total: $1,250.00 (Due Oct 15)\n• Status: Automatically reconciled with accounting'
            }
        }
    };

    let activeScenarioKey = 'client';
    let currentTone = 'executive';
    let typeWriterTimeout = null;

    const senderNameEl = document.getElementById('demo-sender-name');
    const senderEmailEl = document.getElementById('demo-sender-email');
    const senderRoleEl = document.getElementById('demo-sender-role');
    const senderTimeEl = document.getElementById('demo-sender-time');
    const subjectEl = document.getElementById('demo-subject');
    const bodyEl = document.getElementById('demo-body');
    const priorityBadgeEl = document.getElementById('demo-priority-badge');
    const categoryBadgeEl = document.getElementById('demo-category-badge');
    const intentTagEl = document.getElementById('demo-intent-tag');
    const sentimentTagEl = document.getElementById('demo-sentiment-tag');
    const deadlineTagEl = document.getElementById('demo-deadline-tag');
    const summaryEl = document.getElementById('demo-summary');
    const draftTextarea = document.getElementById('demo-draft-textarea');
    const approvedOverlay = document.getElementById('demo-approved-overlay');
    const scenarioBtns = document.querySelectorAll('.demo-scenario-btn');
    const toneBtns = document.querySelectorAll('.demo-tone-btn');
    const editBtn = document.getElementById('demo-edit-btn');
    const approveBtn = document.getElementById('demo-approve-btn');
    const resetBtn = document.getElementById('demo-reset-btn');
    const typingIndicator = document.getElementById('demo-typing-indicator');
    const workbenchPane = document.getElementById('demo-workbench-pane');

    const typeWriter = (text, element, speed = 8) => {
        if (typeWriterTimeout) clearTimeout(typeWriterTimeout);
        element.value = '';
        if (typingIndicator) typingIndicator.classList.remove('hidden');
        
        let i = 0;
        const typeNext = () => {
            if (i < text.length) {
                element.value += text.charAt(i);
                i++;
                typeWriterTimeout = setTimeout(typeNext, speed);
            } else {
                if (typingIndicator) typingIndicator.classList.add('hidden');
            }
        };
        typeNext();
    };

    const updateDemoView = (key, morphTone = false) => {
        const data = scenarios[key];
        if (!data) return;
        activeScenarioKey = key;

        if (approvedOverlay) approvedOverlay.classList.add('hidden');
        if (workbenchPane) window.triggerScanLine(workbenchPane);

        if (senderNameEl) senderNameEl.textContent = data.senderName;
        if (senderEmailEl) senderEmailEl.textContent = `<${data.senderEmail}>`;
        if (senderRoleEl) senderRoleEl.textContent = data.senderRole;
        if (senderTimeEl) senderTimeEl.textContent = data.senderTime;
        if (subjectEl) subjectEl.textContent = data.subject;
        if (bodyEl) bodyEl.textContent = data.body;
        
        if (priorityBadgeEl) {
            priorityBadgeEl.textContent = `${data.priorityTier} (${data.priorityScore}/10)`;
            priorityBadgeEl.className = `status-chip ${data.priorityChipClass}`;
        }
        
        if (categoryBadgeEl) {
            categoryBadgeEl.textContent = data.categoryTag;
        }

        if (intentTagEl) intentTagEl.textContent = data.intent;
        if (sentimentTagEl) sentimentTagEl.textContent = data.sentiment;
        if (deadlineTagEl) deadlineTagEl.textContent = data.deadlineText;
        if (summaryEl) summaryEl.textContent = data.summary;

        const draftContent = data.tones[currentTone] || data.tones.executive;
        if (draftTextarea) {
            if (morphTone) {
                typeWriter(draftContent, draftTextarea, 8);
            } else {
                draftTextarea.value = draftContent;
            }
        }

        scenarioBtns.forEach(btn => {
            const btnKey = btn.getAttribute('data-scenario');
            if (btnKey === key) {
                btn.classList.add('border-[rgba(45,227,200,0.4)]', 'bg-[rgba(45,227,200,0.1)]', 'shadow-md');
                btn.classList.remove('border-white/10', 'bg-white/5');
            } else {
                btn.classList.remove('border-[rgba(45,227,200,0.4)]', 'bg-[rgba(45,227,200,0.1)]', 'shadow-md');
                btn.classList.add('border-white/10', 'bg-white/5');
            }
        });
    };

    scenarioBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const key = btn.getAttribute('data-scenario');
            updateDemoView(key, true);
        });
    });

    toneBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const tone = btn.getAttribute('data-tone');
            if (!tone) return;
            currentTone = tone;

            toneBtns.forEach(b => {
                if (b.getAttribute('data-tone') === tone) {
                    b.classList.add('bg-[var(--cyan)]', 'text-[#090C14]', 'font-bold');
                    b.classList.remove('bg-white/5', 'text-stone-300');
                } else {
                    b.classList.remove('bg-[var(--cyan)]', 'text-[#090C14]', 'font-bold');
                    b.classList.add('bg-white/5', 'text-stone-300');
                }
            });

            const data = scenarios[activeScenarioKey];
            if (data && draftTextarea) {
                const text = data.tones[tone] || data.tones.executive;
                typeWriter(text, draftTextarea, 8);
            }
        });
    });

    if (editBtn && draftTextarea) {
        editBtn.addEventListener('click', () => {
            if (approvedOverlay) approvedOverlay.classList.add('hidden');
            draftTextarea.focus();
            draftTextarea.select();
            showToast('Editing draft reply in active buffer');
        });
    }

    if (approveBtn && approvedOverlay) {
        approveBtn.addEventListener('click', () => {
            approvedOverlay.classList.remove('hidden');
            showToast('✓ Dispatching approved RFC draft via Gmail API');
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            currentTone = 'executive';
            toneBtns.forEach((b, idx) => {
                if (idx === 0) {
                    b.classList.add('bg-[var(--cyan)]', 'text-[#090C14]', 'font-bold');
                    b.classList.remove('bg-white/5', 'text-stone-300');
                } else {
                    b.classList.remove('bg-[var(--cyan)]', 'text-[#090C14]', 'font-bold');
                    b.classList.add('bg-white/5', 'text-stone-300');
                }
            });
            updateDemoView(activeScenarioKey, false);
        });
    }

    // =============================================================
    // 9. FAQ Accordion Animation (Vaultline Style)
    // =============================================================
    const faqToggles = document.querySelectorAll('.faq-toggle');
    faqToggles.forEach(toggle => {
        toggle.addEventListener('click', () => {
            const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
            const content = toggle.nextElementSibling;
            const icon = toggle.querySelector('.faq-icon');

            faqToggles.forEach(otherToggle => {
                if (otherToggle !== toggle) {
                    otherToggle.setAttribute('aria-expanded', 'false');
                    if (otherToggle.nextElementSibling) otherToggle.nextElementSibling.classList.add('hidden');
                    const otherIcon = otherToggle.querySelector('.faq-icon');
                    if (otherIcon) {
                        otherIcon.textContent = '+';
                        otherIcon.classList.remove('rotate-45');
                    }
                }
            });

            if (isExpanded) {
                toggle.setAttribute('aria-expanded', 'false');
                if (content) content.classList.add('hidden');
                if (icon) {
                    icon.textContent = '+';
                    icon.classList.remove('rotate-45');
                }
            } else {
                toggle.setAttribute('aria-expanded', 'true');
                if (content) content.classList.remove('hidden');
                if (icon) {
                    icon.textContent = '×';
                    icon.classList.add('rotate-45');
                }
            }
        });
    });

    // =============================================================
    // 10. Early Access Form Handler
    // =============================================================
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
                    earlyAccessSubmit.innerHTML = `<span class="flex items-center gap-2"><span class="w-3 h-3 border-2 border-white/60 border-t-transparent rounded-full animate-spin"></span> Enrolling...</span>`;
                }
                setTimeout(() => {
                    earlyAccessForm.classList.add('hidden');
                    earlyAccessSuccess.classList.remove('hidden');
                    showToast('🎉 Priority Milo Early Access Seat Confirmed!');
                }, 600);
            }
        });
    }
});
