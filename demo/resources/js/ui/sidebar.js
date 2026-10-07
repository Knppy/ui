const sidebar = (open = true) => ({
    open,
    openMobile: false,
    isMobile: false,
    media: null,
    get state() {
        return this.open ? 'expanded' : 'collapsed';
    },
    init() {
        const saved = document.cookie.split('; ')
            .find((cookie) => cookie.startsWith('sidebar_state='))
            ?.split('=')[1];

        if (saved === 'true' || saved === 'false') {
            this.open = saved === 'true';
        }

        this.media = window.matchMedia('(max-width: 767px)');
        this.isMobile = this.media.matches;
        this.media.addEventListener('change', (event) => {
            this.isMobile = event.matches;
            if (!this.isMobile) {
                this.openMobile = false;
            }
        });
    },
    setOpen(open) {
        this.open = open;
        document.cookie = `sidebar_state=${open}; path=/; max-age=604800; SameSite=Lax`;
        this.$dispatch('sidebar-change', open);
    },
    toggleSidebar() {
        if (this.isMobile) {
            this.openMobile = !this.openMobile;
            return;
        }

        this.setOpen(!this.open);
    },
    closeMobile() {
        this.openMobile = false;
    },
    sidebarStyle(side, collapsible) {
        const hidden = side === 'left' ? 'translateX(-100%)' : 'translateX(100%)';

        if (this.isMobile) {
            return { transform: this.openMobile ? 'translateX(0)' : hidden };
        }

        return {
            transform: !this.open && collapsible === 'offcanvas' ? hidden : 'translateX(0)',
        };
    },
    handleShortcut(event) {
        if (event.key.toLocaleLowerCase() === 'b' && (event.metaKey || event.ctrlKey)) {
            event.preventDefault();
            this.toggleSidebar();
        }
    },
});

export default function (Alpine) {
    Alpine.data('uiSidebar', sidebar);
}
