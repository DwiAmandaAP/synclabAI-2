const jadwalDataMap = new Map();

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.jadwal-data').forEach(el => {
    const id = parseInt(el.dataset.id);
    jadwalDataMap.set(id, {
      id: id,
      id_praktikum: el.dataset.id_praktikum,
      praktikum_nama: el.dataset.praktikum_nama,
      hari: el.dataset.hari,
      jam_mulai: el.dataset.jam_mulai,
      jam_selesai: el.dataset.jam_selesai,
      id_laboratorium: el.dataset.id_laboratorium,
      laboratorium_nama: el.dataset.laboratorium_nama,
      id_dosen: el.dataset.id_dosen,
      dosen_nama: el.dataset.dosen_nama,
      status: el.dataset.status,
      created_at: el.dataset.created_at,
      updated_at: el.dataset.updated_at,
      praktikum: JSON.parse(el.dataset.praktikum || 'null'),
      laboratorium: JSON.parse(el.dataset.laboratorium || 'null'),
      dosen: JSON.parse(el.dataset.dosen || 'null'),
      pertemuans: JSON.parse(el.dataset.pertemuans || '[]')
    });
  });
});

function openAddModal() {
  document.getElementById('addModal').style.display = 'flex';
}

function closeAddModal() {
  document.getElementById('addModal').style.display = 'none';
}

function openEditModal(id) {
  const jadwal = jadwalDataMap.get(id);
  if (!jadwal) {
    console.error('Jadwal not found:', id);
    return;
  }

  if (jadwal.id_praktikum) {
    document.getElementById('edit_id_praktikum').value = jadwal.id_praktikum;
  } else {
    document.getElementById('edit_id_praktikum').value = '';
  }

  if (jadwal.hari) {
    document.getElementById('edit_hari').value = jadwal.hari;
  } else {
    document.getElementById('edit_hari').value = '';
  }

  document.getElementById('edit_jam_mulai').value = jadwal.jam_mulai || '';
  document.getElementById('edit_jam_selesai').value = jadwal.jam_selesai || '';

  if (jadwal.id_laboratorium) {
    document.getElementById('edit_id_laboratorium').value = jadwal.id_laboratorium;
  } else {
    document.getElementById('edit_id_laboratorium').value = '';
  }

  if (jadwal.id_dosen && jadwal.id_dosen !== 'null') {
    document.getElementById('edit_id_dosen').value = jadwal.id_dosen;
  } else {
    document.getElementById('edit_id_dosen').value = '';
  }

  document.getElementById('status').value = jadwal.status || 'Dibuka';

  document.getElementById('editModal').style.display = 'flex';
}

function openDetailModal(id) {
  const jadwal = jadwalDataMap.get(id);
  if (!jadwal) return;

  let html = `
                <div class="detail-section">
                    <h4>Informasi Jadwal</h4>
                    <table>
                        <tr><th>Praktikum</th><td>${escapeHtml(jadwal.praktikum_nama || '-')}</td></tr>
                        <tr><th>Hari</th><td>${escapeHtml(jadwal.hari || '-')}</td>
                        <tr><th>Jam Mulai</th><td>${escapeHtml(jadwal.jam_mulai || '-')}</td>
                        <tr><th>Jam Selesai</th><td>${escapeHtml(jadwal.jam_selesai || '-')}</td>
                        <tr><th>laboratorium</th><td>${escapeHtml(jadwal.laboratorium_nama || '-')}</td>
                        <tr><th>Dosen</th><td>${escapeHtml(jadwal.dosen_nama || '-')}</td>
                        <tr><th>Status</th><td><span class="badge badge-${jadwal.status}">${escapeHtml(jadwal.status || 'active')}</span></td>
                        <tr><th>Dibuat Pada</th><td>${formatDate(jadwal.created_at)}</td>
                        <tr><th>Terakhir Update</th><td>${formatDate(jadwal.updated_at)}</td>
                    </table>
                </div>
            `;

  if (jadwal.praktikum) {
    html += `
                    <div class="detail-section">
                        <h4>📚 Detail Praktikum</h4>
                        <table>
                            <tr><th>Kode Praktikum</th><td>${escapeHtml(jadwal.praktikum.kode_praktikum || '-')}</td>
                            <tr><th>Nama Praktikum</th><td>${escapeHtml(jadwal.praktikum.nama_praktikum || '-')}</td>
                            <tr><th>Angkatan</th><td>${escapeHtml(jadwal.praktikum.angkatan || '-')}</td>
                            <tr><th>Semester</th><td>${escapeHtml(jadwal.praktikum.semester || '-')}</td>
                        </table>
                    </div>
                `;
  }

  if (jadwal.laboratorium) {
    html += `
                    <div class="detail-section">
                        <h4>🏢 Detail laboratorium</h4>
                        <table>
                            <tr><th>Kode laboratorium</th><td>${escapeHtml(jadwal.laboratorium.kode_laboratorium || '-')}</td>
                            <tr><th>Nama laboratorium</th><td>${escapeHtml(jadwal.laboratorium.nama_laboratorium || '-')}</td>
                            <tr><th>Kapasitas</th><td>${escapeHtml(jadwal.laboratorium.kapasitas || '-')}</td>
                        </table>
                    </div>
                `;
  }

  if (jadwal.dosen) {
    html += `
                    <div class="detail-section">
                        <h4>👨‍🏫 Detail dosen</h4>
                        <table>
                            <tr><th>Nomor Induk</th><td>${escapeHtml(jadwal.dosen.nomor_induk || '-')}</td>
                            <tr><th>Nama</th><td>${escapeHtml(jadwal.dosen.nama || '-')}</td>
                            <tr><th>Email</th><td>${escapeHtml(jadwal.dosen.email || '-')}</td>
                        </table>
                    </div>
                `;
  }

  if (jadwal.pertemuans && jadwal.pertemuans.length > 0) {
    html += `
                    <div class="detail-section">
                        <h4>📋 Daftar Pertemuan</h4>
                        <table>
                            <thead>
                                <tr><th>Nama Pertemuan</th><th>Pertemuan Ke-</th><th>Deskripsi</th></tr>
                            </thead>
                            <tbody>
                `;
    jadwal.pertemuans.forEach(pertemuan => {
      html += `
                        <tr>
                            <td>${escapeHtml(pertemuan.nama_pertemuan || '-')}</td>
                            <td>${escapeHtml(pertemuan.pertemuan_ke || '-')}</td>
                            <td>${escapeHtml(pertemuan.deskripsi_pertemuan || '-')}</td>
                        </tr>
                    `;
    });
    html += `</tbody></table></div>`;
  } else {
    html += `
                    <div class="detail-section">
                        <h4>📋 Daftar Pertemuan</h4>
                        <p style="padding: 15px; margin: 0; color: #666;">Belum ada pertemuan untuk jadwal ini</p>
                    </div>
                `;
  }

  document.getElementById('detailContent').innerHTML = html;
  document.getElementById('detailModal').style.display = 'flex';
}

function escapeHtml(str) {
  if (!str) return '';
  return String(str).replace(/[&<>]/g, function (m) {
    if (m === '&') return '&amp;';
    if (m === '<') return '&lt;';
    if (m === '>') return '&gt;';
    return m;
  });
}

function formatDate(dateString) {
  if (!dateString) return '-';
  const date = new Date(dateString);
  return date.toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
}

window.onclick = function (event) {
  if (event.target.classList && event.target.classList.contains('modal')) {
    event.target.style.display = 'none';
  }
}