<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * templates/footer.php
 * Penutup layout dan script JavaScript global.
 */
?>
    </div><!-- /page-body -->
</main><!-- /main-content -->
</div><!-- /app-wrapper -->

<!-- JavaScript Global -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Auto-close notifikasi alert setelah 4 detik
    document.querySelectorAll('.alert').forEach(function(el) {
        setTimeout(function() {
            el.style.transition = 'opacity .4s, transform .4s';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-10px)';
            setTimeout(function() { el.remove(); }, 400);
        }, 4000);
    });
});
</script>
</body>
</html>
