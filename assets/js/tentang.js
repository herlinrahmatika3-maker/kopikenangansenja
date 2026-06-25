var params = new URLSearchParams(window.location.search);
if (params.get('login') === 'sukses') {
  Swal.fire({
    icon: 'success',
    title: 'Selamat Datang!',
    text: 'Anda berhasil masuk ke sistem.',
    confirmButtonColor: '#c8842a',
    timer: 2000,
    timerProgressBar: true,
    showConfirmButton: false
  });
  history.replaceState(null, '', 'tentang.php');
}
