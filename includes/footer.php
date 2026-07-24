    </div><!-- end content-area -->
    </div><!-- end main-content -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // BUG-009 FIX: alerts now stay visible longer and can be dismissed manually
    document.querySelectorAll('.alert-auto-hide').forEach(el => {
        el.style.position = 'relative';
        el.style.paddingRight = '40px';
        const closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.className = 'btn-close';
        closeBtn.setAttribute('aria-label', 'Close');
        closeBtn.style.cssText = 'position:absolute;top:12px;right:14px;';
        closeBtn.onclick = () => el.remove();
        el.appendChild(closeBtn);
    });
    setTimeout(() => {
        document.querySelectorAll('.alert-auto-hide').forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 8000);
    </script>
</body>
</html>
