        // Chức năng chuyển đổi sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('active');
                document.querySelector('.sidebar-backdrop').classList.toggle('active');
            } else {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            }
        });

        // Đóng sidebar khi nhấp vào backdrop
        document.querySelector('.sidebar-backdrop').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.remove('active');
            this.classList.remove('active');
        });

        // Xử lý thay đổi kích thước cửa sổ
        window.addEventListener('resize', function() {
            const sidebar = document.querySelector('.sidebar');
            const backdrop = document.querySelector('.sidebar-backdrop');
            
            if (window.innerWidth > 768) {
                sidebar.classList.remove('active');
                backdrop.classList.remove('active');
            }
        });