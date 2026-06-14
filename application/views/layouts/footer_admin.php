        </div><!-- /page-body -->
    </main><!-- /main-content -->
</div><!-- /app-wrapper -->

<script>
// Auto-close alert setelah 4 detik
document.querySelectorAll('.alert').forEach(function(el) {
    setTimeout(function() {
        el.style.transition = 'opacity .4s';
        el.style.opacity = '0';
        setTimeout(function() { el.remove(); }, 400);
    }, 4000);
});
</script>
</body>
</html>
