document.getElementById('togglePass').addEventListener('click', function () {
  var pwd = document.getElementById('inputPassword');
  var icon = document.getElementById('eyeIcon');
  if (pwd.type === 'password') {
    pwd.type = 'text';
    icon.classList.replace('fa-eye', 'fa-eye-slash');
  } else {
    pwd.type = 'password';
    icon.classList.replace('fa-eye-slash', 'fa-eye');
  }
});

var params = new URLSearchParams(window.location.search);

if (params.get('logout') === '1') {
  Swal.fire({
    icon: 'success',
    title: 'Logout Berhasil!',
    text: 'Anda telah keluar dari sistem.',
    confirmButtonColor: '#c8842a',
    timer: 2500,
    timerProgressBar: true,
    showConfirmButton: false
  });
}

if (params.get('error') === '1') {
  Swal.fire({
    icon: 'error',
    title: 'Login Gagal!',
    text: 'Username atau password yang Anda masukkan salah.',
    confirmButtonColor: '#c8842a',
    confirmButtonText: 'Coba Lagi'
  });
}

if (params.get('akses') === 'ditolak') {
  Swal.fire({
    icon: 'warning',
    title: 'Akses Ditolak!',
    text: 'Anda harus login terlebih dahulu untuk mengakses halaman tersebut.',
    confirmButtonColor: '#c8842a',
    confirmButtonText: 'OK'
  });
}
