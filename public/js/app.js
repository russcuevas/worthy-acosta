/**
 * Worthy Acosta Dashboard & UI JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
    const appSidebar = document.getElementById('appSidebar');

    // Restore desktop sidebar collapsed state preference
    const isDesktop = window.innerWidth > 992;
    if (isDesktop && localStorage.getItem('worthy_sidebar_collapsed') === 'true') {
        document.body.classList.add('sidebar-collapsed');
    }

    // Toggle Sidebar (Desktop Hide/Show & Mobile Open/Close)
    if (sidebarToggleBtn) {
        sidebarToggleBtn.addEventListener('click', function () {
            if (window.innerWidth > 992) {
                document.body.classList.toggle('sidebar-collapsed');
                const isCollapsed = document.body.classList.contains('sidebar-collapsed');
                localStorage.setItem('worthy_sidebar_collapsed', isCollapsed);
            } else {
                if (appSidebar) {
                    appSidebar.classList.toggle('sidebar-open');
                }
            }
        });
    }

    // Close button for mobile sidebar
    if (sidebarCloseBtn && appSidebar) {
        sidebarCloseBtn.addEventListener('click', function () {
            appSidebar.classList.remove('sidebar-open');
        });
    }

    // Close mobile sidebar on click outside
    document.addEventListener('click', function (e) {
        if (window.innerWidth <= 992 && appSidebar && appSidebar.classList.contains('sidebar-open')) {
            if (!appSidebar.contains(e.target) && sidebarToggleBtn && !sidebarToggleBtn.contains(e.target)) {
                appSidebar.classList.remove('sidebar-open');
            }
        }
    });

    // Auth Role Switcher Tabs (Login Page)
    const roleAdminBtn = document.getElementById('roleAdminBtn');
    const roleAssistantBtn = document.getElementById('roleAssistantBtn');
    const roleInput = document.getElementById('roleInput');
    const roleDisplayLabel = document.getElementById('roleDisplayLabel');

    if (roleAdminBtn && roleAssistantBtn && roleInput) {
        roleAdminBtn.addEventListener('click', function () {
            roleAdminBtn.classList.add('active');
            roleAssistantBtn.classList.remove('active');
            roleInput.value = 'admin';
            if (roleDisplayLabel) roleDisplayLabel.textContent = 'Admin Portal';
        });

        roleAssistantBtn.addEventListener('click', function () {
            roleAssistantBtn.classList.add('active');
            roleAdminBtn.classList.remove('active');
            roleInput.value = 'assistant';
            if (roleDisplayLabel) roleDisplayLabel.textContent = 'Assistant Portal';
        });
    }
});
