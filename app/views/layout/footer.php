  </div>
</main>
</div>

<div class="toast-stack" id="toastStack" aria-live="polite" aria-atomic="true"></div>

<script src="<?= $_base_url ?>/assets/js/sidebar.js"></script>
<script src="<?= $_base_url ?>/assets/js/toast.js"></script>
<script src="<?= $_base_url ?>/assets/js/modal.js"></script>
<script src="<?= $_base_url ?>/assets/js/darkmode.js"></script>
<script src="<?= $_base_url ?>/assets/js/active-nav.js"></script>
<script src="<?= $_base_url ?>/assets/js/app.js"></script>

<script>
  // Hook botón tema
  const btnTema = document.getElementById('btnTema');
  if (btnTema) btnTema.addEventListener('click', () => window.Theme?.toggle());
</script>

</body>
</html>
