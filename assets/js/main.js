/* ==========================================================================
   Eghtesadran Client-side Interactions
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialize Lucide Icons
    const initLucide = () => {
        if (window.lucide) {
            window.lucide.createIcons();
        }
    };
    initLucide();

    // 2. Mobile Menu Drawer Logic
    const mobileDrawer = document.getElementById('mobile-drawer');
    const menuDrawerPanel = document.getElementById('menu-drawer-panel');
    const openMenuBtns = document.querySelectorAll('.open-menu-btn');
    const closeMenuBtn = document.getElementById('close-menu-btn');
    const menuBackdrop = document.getElementById('menu-backdrop');

    if (mobileDrawer && menuDrawerPanel && menuBackdrop) {
        const openMenu = () => {
            mobileDrawer.classList.remove('pointer-events-none');
            menuBackdrop.classList.remove('opacity-0', 'pointer-events-none');
            menuBackdrop.classList.add('opacity-100', 'pointer-events-auto');
            menuDrawerPanel.classList.remove('translate-x-full');
            menuDrawerPanel.classList.add('translate-x-0');
        };

        const closeMenu = () => {
            menuDrawerPanel.classList.remove('translate-x-0');
            menuDrawerPanel.classList.add('translate-x-full');
            menuBackdrop.classList.remove('opacity-100', 'pointer-events-auto');
            menuBackdrop.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => {
                mobileDrawer.classList.add('pointer-events-none');
            }, 200);
        };

        openMenuBtns.forEach(btn => btn.addEventListener('click', openMenu));
        if (closeMenuBtn) closeMenuBtn.addEventListener('click', closeMenu);
        menuBackdrop.addEventListener('click', closeMenu);
    }

    // 3. Search Modal Logic (Premium integration)
    const searchModal = document.getElementById('search-modal');
    const openSearchBtns = document.querySelectorAll('.open-search-btn');
    const closeSearchBtn = document.getElementById('close-search-btn');
    const searchBackdrop = document.getElementById('search-backdrop');
    const searchInput = document.getElementById('search-input');

    if (searchModal) {
        const openSearch = () => {
            searchModal.classList.remove('hidden', 'pointer-events-none');
            setTimeout(() => {
                if (searchInput) searchInput.focus();
            }, 100);
        };

        const closeSearch = () => {
            searchModal.classList.add('hidden', 'pointer-events-none');
        };

        openSearchBtns.forEach(btn => btn.addEventListener('click', openSearch));
        if (closeSearchBtn) closeSearchBtn.addEventListener('click', closeSearch);
        if (searchBackdrop) searchBackdrop.addEventListener('click', closeSearch);

        // Close search on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) {
                closeSearch();
            }
        });
    }

    // 4. Scroll-dependent Header & Back-to-Top Button
    const stickyHeader = document.getElementById('sticky-header');
    const backToTopBtn = document.getElementById('back-to-top');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 200) {
            if (stickyHeader) {
                stickyHeader.classList.remove('-translate-y-full');
                stickyHeader.classList.add('translate-y-0');
            }
            if (backToTopBtn) {
                backToTopBtn.classList.remove('opacity-0', 'translate-y-16', 'scale-50', 'pointer-events-none');
                backToTopBtn.classList.add('opacity-100', 'translate-y-0', 'scale-100');
            }
        } else {
            if (stickyHeader) {
                stickyHeader.classList.remove('translate-y-0');
                stickyHeader.classList.add('-translate-y-full');
            }
            if (backToTopBtn) {
                backToTopBtn.classList.remove('opacity-100', 'translate-y-0', 'scale-100');
                backToTopBtn.classList.add('opacity-0', 'translate-y-16', 'scale-50', 'pointer-events-none');
            }
        }
    });

    if (backToTopBtn) {
        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // 5. Dark Mode Toggle (Multi-button support: Main Header, Sticky Header, and Drawer)
    const themeToggles = document.querySelectorAll('.theme-toggle-btn');

    // Sync theme icons across all toggle buttons
    const updateThemeIcons = (isDark) => {
        themeToggles.forEach(btn => {
            const icon = btn.querySelector('.theme-toggle-icon');
            if (icon) {
                if (isDark) {
                    icon.setAttribute('data-lucide', 'sun');
                } else {
                    icon.setAttribute('data-lucide', 'moon');
                }
            }
        });
        initLucide();
    };

    // Initial state check
    const isDarkModeActive = () => {
        if (localStorage.getItem('color-theme')) {
            return localStorage.getItem('color-theme') === 'dark';
        }
        return document.documentElement.classList.contains('dark');
    };

    // Initialize icons based on current state
    updateThemeIcons(isDarkModeActive());

    themeToggles.forEach(btn => {
        btn.addEventListener('click', () => {
            const willBeDark = !isDarkModeActive();
            if (willBeDark) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            }
            updateThemeIcons(willBeDark);
        });
    });

    // 6. Mobile Footer Accordion
    const footerAccordionTriggers = document.querySelectorAll('.footer-accordion-trigger');
    footerAccordionTriggers.forEach(trigger => {
        trigger.addEventListener('click', () => {
            if (window.innerWidth >= 640) return; // Only apply on mobile (sm breakpoint is 640px)
            const content = trigger.nextElementSibling;
            const icon = trigger.querySelector('.footer-accordion-icon');
            
            // Toggle visibility
            content.classList.toggle('hidden');
            
            // Toggle icon rotation
            if (content.classList.contains('hidden')) {
                if (icon) icon.style.transform = 'rotate(0deg)';
            } else {
                if (icon) icon.style.transform = 'rotate(180deg)';
            }
        });
    });

    // 7. Single Article Accessibility Controls (Font size and Line height)
    const btnFontPlus = document.getElementById('btn-font-plus');
    const btnFontMinus = document.getElementById('btn-font-minus');
    const btnLinePlus = document.getElementById('btn-line-plus');
    const btnLineMinus = document.getElementById('btn-line-minus');
    const articleContent = document.querySelector('.article-content');

    if (articleContent) {
        // Font sizes: text-sm (default on mobile), text-base (default on lg), text-lg, text-xl, text-2xl
        let fontSizes = ['text-sm md:text-base', 'text-base md:text-lg', 'text-lg md:text-xl', 'text-xl md:text-2xl', 'text-2xl md:text-3xl'];
        let currentFontIndex = localStorage.getItem('article-font-index') ? parseInt(localStorage.getItem('article-font-index')) : 0;

        const updateFont = () => {
            fontSizes.forEach(cls => {
                cls.split(' ').forEach(c => articleContent.classList.remove(c));
            });
            fontSizes[currentFontIndex].split(' ').forEach(c => articleContent.classList.add(c));
            localStorage.setItem('article-font-index', currentFontIndex);
        };

        if(btnFontPlus) {
            btnFontPlus.addEventListener('click', () => {
                if (currentFontIndex < fontSizes.length - 1) {
                    currentFontIndex++;
                    updateFont();
                }
            });
        }
        
        if(btnFontMinus) {
            btnFontMinus.addEventListener('click', () => {
                if (currentFontIndex > 0) {
                    currentFontIndex--;
                    updateFont();
                }
            });
        }

        updateFont(); // Init

        // Line heights
        let lineHeights = ['leading-relaxed', 'leading-loose', 'leading-8 md:leading-9', 'leading-9 md:leading-10', 'leading-10 md:leading-[3rem]'];
        let currentLineIndex = localStorage.getItem('article-line-index') ? parseInt(localStorage.getItem('article-line-index')) : 0;

        const updateLine = () => {
            lineHeights.forEach(cls => {
                cls.split(' ').forEach(c => articleContent.classList.remove(c));
            });
            lineHeights[currentLineIndex].split(' ').forEach(c => articleContent.classList.add(c));
            localStorage.setItem('article-line-index', currentLineIndex);
        };

        if(btnLinePlus) {
            btnLinePlus.addEventListener('click', () => {
                if (currentLineIndex < lineHeights.length - 1) {
                    currentLineIndex++;
                    updateLine();
                }
            });
        }
        
        if(btnLineMinus) {
            btnLineMinus.addEventListener('click', () => {
                if (currentLineIndex > 0) {
                    currentLineIndex--;
                    updateLine();
                }
            });
        }
        
        updateLine(); // Init
    }

    // 8. Copy Shortlink Logic
    const copyShortlinkBtn = document.getElementById('copy-shortlink-btn');
    const shortlinkInput = document.getElementById('shortlink-input');

    function showToast(message) {
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'fixed bottom-5 left-5 z-[99999] flex flex-col gap-2 pointer-events-none';
            document.body.appendChild(toastContainer);
        }
        
        const toast = document.createElement('div');
        toast.className = 'flex items-center gap-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xl rounded-xl p-4 min-w-[280px] max-w-sm pointer-events-auto transform translate-y-10 opacity-0 transition-all duration-300 ease-out';
        toast.style.direction = 'rtl';
        
        toast.innerHTML = `
            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-50 dark:bg-emerald-950/40 shrink-0 text-emerald-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle-2"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
            </div>
            <div class="flex-1 text-sm font-semibold text-slate-800 dark:text-slate-200">
                ${message}
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        // Trigger transition
        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-10', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        });
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-10', 'opacity-0');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }

    if (copyShortlinkBtn && shortlinkInput) {
        copyShortlinkBtn.addEventListener('click', () => {
            const textToCopy = shortlinkInput.value;

            const performCopy = () => {
                // Try modern Clipboard API first
                if (navigator.clipboard && window.isSecureContext) {
                    return navigator.clipboard.writeText(textToCopy);
                } else {
                    // Fallback to select & document.execCommand
                    return new Promise((resolve, reject) => {
                        try {
                            shortlinkInput.select();
                            shortlinkInput.setSelectionRange(0, 99999); // For mobile devices
                            const successful = document.execCommand('copy');
                            if (successful) {
                                resolve();
                            } else {
                                reject(new Error('Fallback copy failed'));
                            }
                        } catch (err) {
                            reject(err);
                        }
                    });
                }
            };

            performCopy()
                .then(() => {
                    // Show temporary checkmark inside button
                    const originalHTML = copyShortlinkBtn.innerHTML;
                    copyShortlinkBtn.innerHTML = '<i data-lucide="check" class="w-3.5 h-3.5 text-green-500"></i>';
                    if (window.lucide) {
                        window.lucide.createIcons();
                    }
                    
                    // Show premium toast
                    showToast('لینک کوتاه با موفقیت کپی شد.');
                    
                    setTimeout(() => {
                        copyShortlinkBtn.innerHTML = originalHTML;
                        if (window.lucide) {
                            window.lucide.createIcons();
                        }
                    }, 2000);
                })
                .catch(err => {
                    console.error('Failed to copy: ', err);
                    showToast('خطا در کپی کردن لینک.');
                });
        });
    }

});
