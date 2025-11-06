function dashboard() {
    window.dashboardState = { darkMode: false }; // global state

    return {
        sidebarOpen: window.innerWidth >= 992,
        darkMode: false,
        activeMenu: "dashboard",
        pageTitle: "Dashboard",
        breadcrumb: ["Home", "Dashboard"],

        init() {
            this.handleResize();

            // cek body class dulu
            this.darkMode = document.body.classList.contains("dark");
            window.dashboardState.darkMode = this.darkMode;

            // cek localStorage (menimpa body class kalau ada)
            const savedTheme = localStorage.getItem("theme");
            if (savedTheme) {
                this.darkMode = savedTheme === "dark";
                document.body.classList.toggle("dark", this.darkMode);
                window.dashboardState.darkMode = this.darkMode;
            }

            // mapping route -> breadcrumb
            const currentRoute = document.body.dataset.route;
            const routeBreadcrumbs = {
                "dashboard.admin": ["Home", "/", "Dashboard"],
                "admin.user": ["Home", "/", "Pengguna"],
                "admin.statistik": ["Home", "/", "Statistik Website"],
                "admin.laporan": ["Home", "/", "Laporan"],
                "admin.aktivitas": ["Home", "/", "Aktivitas Terbaru"],
                "admin.tag": ["Home", "/", "Tag"],
                "admin.tag.create": ["Home", "/", "Tambah Tag"],
                "admin.artikel.detail": ["Home", "/", "Artikel", "/", "Detail Artikel"],
                "admin.repo.detail": ["Home", "/", "Repository", "/", "Detail Repository"],
                "admin.user.create": ["Home", "/", "Pengguna", "/", "Tambah Penguna"],
                "admin.user.edit": ["Home", "/", "Pengguna", "/", "Edit Pengguna"],
                "admin.trash.artikel": ["Home", "/", "Trash", "/", "Sampah Artikel"],
                "admin.trash.repo": ["Home", "/", "Trash", "/", "Sampah Repository "],
                "admin.trash.user": ["Home", "/", "Trash", "/", "Sampah Pengguna "],
                "admin.saran": ["Home", "/", "Saran "],
            };
            this.breadcrumb = routeBreadcrumbs[currentRoute] || [
                "Home",
                "Dashboard",
            ];
            this.pageTitle = this.breadcrumb[this.breadcrumb.length - 1];
        },

        handleResize() {
            this.sidebarOpen = window.innerWidth >= 992;
        },

        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem("theme", this.darkMode ? "dark" : "light");
            document.body.classList.toggle("dark", this.darkMode);
            window.dashboardState.darkMode = this.darkMode;

            // dispatch event untuk chart update
            window.dispatchEvent(
                new CustomEvent("theme-changed", {
                    detail: { darkMode: this.darkMode },
                })
            );
        },

        setActiveMenu(menu) {
            const menuBreadcrumbs = {
                dashboard: ["Home", "Dashboard"],
                user: ["Home", "Pengguna"],
                statistik: ["Home", "Statistik Website"],
                laporan: ["Home", "Laporan"],
                aktivitas: ["Home", "Aktivitas Terbaru"],
                tag: ["Home", "Kelola Tag"],
            };
            this.breadcrumb = menuBreadcrumbs[menu] || ["Home", "Dashboard"];
            this.pageTitle = this.breadcrumb[this.breadcrumb.length - 1];

            if (window.innerWidth < 992) this.sidebarOpen = false;
        },
    };
}
