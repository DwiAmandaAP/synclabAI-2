document.getElementById('searchPraktikum')?.addEventListener('keyup', function (e) {
  const search = e.target.value.toLowerCase();
  document.querySelectorAll('.praktikum-card').forEach(card => {
    const nama = card.dataset.nama?.toLowerCase() || '';
    card.style.display = nama.includes(search) ? '' : 'none';
  });
});

// Edit praktikum
document.querySelectorAll('.btn-edit').forEach(btn => {
  btn.addEventListener('click', function () {
    const praktikum = JSON.parse(this.dataset.praktikum);
    document.getElementById('editNama').value = praktikum.nama_praktikum;
    document.getElementById('editKode').value = praktikum.kode_praktikum;
    document.getElementById('editAngkatan').value = praktikum.angkatan;
    document.getElementById('editSemester').value = praktikum.semester;
    document.getElementById('formEditPraktikum').action = `/laboran/updatePraktikum/${praktikum.id}`;
    document.getElementById('modalPraktikumEdit').classList.add('show');
  });
});

// Delete praktikum
let deleteId = null;
document.querySelectorAll('.btn-delete').forEach(btn => {
  btn.addEventListener('click', function () {
    deleteId = this.dataset.id;
    document.getElementById('deletePraktikumNama').innerText = this.dataset.nama;
    document.getElementById('modalPraktikumDelete').classList.add('show');
  });
});
document.getElementById('confirmDeleteBtn')?.addEventListener('click', function () {
  if (deleteId) {
    fetch(`/laboran/deletePraktikum/${deleteId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      }
    }).then(() => window.location.reload());
  }
});

// Detail praktikum
function showPraktikumDetail(praktikum) {
  document.getElementById('detailNama').innerText = praktikum.nama_praktikum;
  document.getElementById('detailKode').innerText = praktikum.kode_praktikum;
  document.getElementById('detailAngkatan').innerText = praktikum.angkatan;
  document.getElementById('detailSemester').innerText = praktikum.semester;
  document.getElementById('modalPraktikumDetail').classList.add('show');
}
document.querySelectorAll('.btn-detail').forEach(btn => {
  btn.addEventListener('click', function () {
    showPraktikumDetail(JSON.parse(this.dataset.praktikum));
  });
});

// Asisten detail
function showAsistenDetail(asisten) {
  const content = `
                <p><strong>Nama:</strong> ${asisten.nama}</p>
                <p><strong>Email:</strong> ${asisten.email || '-'}</p>
                <p><strong>No HP:</strong> ${asisten.nohp || '-'}</p>
                <p><strong>Jumlah Kelas:</strong> ${asisten.jadwals_count || 0}</p>
                <p><strong>Status:</strong> <span class="badge ${asisten.jadwals_count > 2 ? 'badge-warning' : 'badge-success'}">${asisten.jadwals_count > 2 ? 'Overload' : 'Normal'}</span></p>
            `;
  document.getElementById('asistenDetailContent').innerHTML = content;
  document.getElementById('modalAsistenDetail').classList.add('show');
}

// Asisten jadwal
function showAsistenJadwal(id, nama) {
  document.getElementById('asistenNamaJadwal').innerText = nama;
  fetch(`/laboran/asisten-jadwal/${id}`)
    .then(res => res.json())
    .then(data => {
      let html = '';
      if (data.length === 0) {
        html = '<p style="text-align:center;color:#999;">Tidak ada jadwal</p>';
      } else {
        html = '<ul style="list-style:none;padding:0;">';
        data.forEach(j => {
          html += `<li style="padding:8px 0;border-bottom:1px solid #eee;">
                                <strong>${j.hari}</strong> - ${j.jam_mulai} s/d ${j.jam_selesai}<br>
                                ${j.praktikum?.nama_praktikum || '-'} - ${j.laboratorium?.nama_laboratorium || '-'}
                            </li>`;
        });
        html += '</ul>';
      }
      document.getElementById('asistenJadwalContent').innerHTML = html;
      document.getElementById('modalAsistenJadwal').classList.add('show');
    });
}

// Jadwal detail
function showJadwalDetail(jadwal) {
  const content = `
                <p><strong>Praktikum:</strong> ${jadwal.praktikum?.nama_praktikum || '-'}</p>
                <p><strong>Hari:</strong> ${jadwal.hari}</p>
                <p><strong>Jam:</strong> ${jadwal.jam_mulai} - ${jadwal.jam_selesai}</p>
                <p><strong>Laboratorium:</strong> ${jadwal.laboratorium?.nama_laboratorium || '-'}</p>
                <p><strong>Dosen:</strong> ${jadwal.dosen?.nama || '-'}</p>
                <p><strong>Max Peserta:</strong> ${jadwal.jumlah_max_peserta || '-'}</p>
                <p><strong>Status:</strong> ${jadwal.bentrok ? '❌ Bentrok' : (jadwal.status || 'Aktif')}</p>
            `;
  document.getElementById('jadwalDetailContent').innerHTML = content;
  document.getElementById('modalJadwalDetail').classList.add('show');
}

// Export functions
function exportLaporan(type, format) {
  const praktikumId = document.getElementById('filterPraktikumLaporan')?.value || '';
  const semester = document.getElementById('filterSemesterLaporan')?.value || '';
  const date = document.getElementById('filterDateLaporan')?.value || '';
  window.location.href = `/laboran/export/${type}/${format}?praktikum_id=${praktikumId}&semester=${semester}&date=${date}`;
}

function showDetailNilai() {
  window.location.href = '/laboran/master-nilai';
}

function showDetailLaporan() {
  window.location.href = '/laboran/master-laporan';
}

function closeModal(modalId) {
  document.getElementById(modalId).classList.remove('show');
}

window.onclick = function (event) {
  if (event.target.classList && event.target.classList.contains('modal')) {
    event.target.classList.remove('show');
  }
}