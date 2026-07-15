// JS loading
document.addEventListener("DOMContentLoaded", function() {
    setTimeout(function() {
        document.querySelector('.preloader').classList.add('hidden');
    }, 500); // 0.5 detik
});

window.addEventListener('load', function () {
        const preloader = document.querySelector('.preloader');
        preloader.classList.add('hidden');

        // Hapus overflow hidden setelah preloader hilang
        document.body.classList.remove('preloader-active');
});


// Notif Flashdata 
setTimeout(function() {
    let alert = document.querySelector('.alert');
    if (alert) {
        alert.style.transition = "opacity 0.5s";
        alert.style.opacity = "0";
        setTimeout(() => alert.remove(), 500); // Hapus setelah animasi selesai
    }
}, 5000); // 1 detik


// Format Rupiah
const rupiah = document.getElementById('rupiah');

rupiah.addEventListener('input', function (e) {
    let value = this.value.replace(/\D/g, '');
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    this.value = value;
});


function previewAvatar(input) {
    const preview = document.getElementById('avatarPreview');
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

// Select 2 Responsive
$(document).ready(function() {
    $('.select2').select2({
        width: '100%' // Tambahkan ini juga
    });
});

$('.select2').select2({
    dropdownAutoWidth: true,
    width: '100%' // atau 'resolve' jika ingin fleksibel
});
