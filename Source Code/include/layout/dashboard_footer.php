        </main>
        
        <!-- Footer -->
        <footer class="footer mt-auto py-3 bg-light">
            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div class="text-muted">
                        &copy; <?php echo date('Y'); ?> Vũ Chí Linh Computer
                    </div>
                    <div class="text-muted">
                        Design by <a href="https://github.com/Sallydead" class="text-muted text-decoration-none">Vũ Chí Linh</a>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Bootstrap Bundle JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Custom JS -->
        <script>
        // Toggle Sidebar
        const toggleBtn = document.getElementById('sidebarToggle');
        const body = document.body;
        const backdrop = document.querySelector('.sidebar-backdrop');

        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            body.classList.toggle('sidebar-open');
        });

        // Close Sidebar when clicking backdrop
        backdrop.addEventListener('click', function() {
            body.classList.remove('sidebar-open');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (body.classList.contains('sidebar-open') && 
                !e.target.closest('.sidebar') && 
                !e.target.closest('#sidebarToggle')) {
                body.classList.remove('sidebar-open');
            }
        });

        // Auto close alerts
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(alert => {
            setTimeout(() => {
                bootstrap.Alert.getOrCreateInstance(alert).close();
            }, 5000);
        });
        </script>
    </body>
</html> 