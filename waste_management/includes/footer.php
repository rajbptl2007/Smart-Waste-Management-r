    </div><!-- end content-area -->
    </div><!-- end main-content -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Auto-hide alerts
    setTimeout(() => {
        document.querySelectorAll('.alert-auto-hide').forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 4000);
    </script>
</body>
</html>
