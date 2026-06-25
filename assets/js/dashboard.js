function konfirmasiLogout() {
  Swal.fire({
    title: 'Konfirmasi Logout',
    text: 'Apakah Anda yakin ingin keluar?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#c8842a',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Ya, Logout',
    cancelButtonText: 'Batal'
  }).then(function (result) {
    if (result.isConfirmed) {
      window.location.href = 'logout.php';
    }
  });
  return false;
}
