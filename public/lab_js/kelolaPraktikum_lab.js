let data = [];
let editIndex = null;

function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('active');
}

document.addEventListener("click", function (e) {
  const sidebar = document.getElementById("sidebar");
  const toggle = document.querySelector(".toggle");

  if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
    sidebar.classList.remove("active");
  }
});

function openAdd() {
  document.getElementById("formModal").style.display = "flex";
  document.getElementById("modalTitle").innerText = "Add Practicum";

  clearForm();
  editIndex = null;
}

function closeModal() {
  document.getElementById("formModal").style.display = "none";
}

function clearForm() {
  document.getElementById("mk").value = "";
  document.getElementById("pertemuan").value = "";
  document.getElementById("praktikum").value = "";
  document.getElementById("deskripsi").value = "";
  document.getElementById("kelas").value = "";
  document.getElementById("status").value = "Active";
}

function openEditModal(id) {
  const item = praktikumData.find(p => p.id === id);
  if (item) {
    document.getElementById('edit_kode').value = item.kode_praktikum;
    document.getElementById('edit_nama').value = item.nama_praktikum;
    document.getElementById('edit_angkatan').value = item.angkatan;
    document.getElementById('edit_semester').value = item.semester;
    document.getElementById('editModal').style.display = 'flex';
  }
}

function openDetailModal(id) {
  const item = praktikumData.find(p => p.id === id);
  if (!item) return;

  document.getElementById('detailInfo').innerHTML = `
                <div class="detail-item">
                    <span class="detail-label">Kode Praktikum</span>
                    <span class="detail-value">${escapeHtml(item.kode_praktikum)}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Nama Praktikum</span>
                    <span class="detail-value">${escapeHtml(item.nama_praktikum)}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Angkatan</span>
                    <span class="detail-value">${escapeHtml(item.angkatan)}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Semester</span>
                    <span class="detail-value">Semester ${escapeHtml(item.semester)}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value"><span class="status-badge status-active">Active</span></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Dibuat Pada</span>
                    <span class="detail-value">${item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID') : '-'}</span>
                </div>
            `;

  const jadwalList = item.jadwals || [];
  const asistenList = item.asisten || [];
  const mahasiswaList = item.mahasiswa || [];

  console.log('Item:', item);
  console.log('Jadwal:', jadwalList);
  console.log('Asisten:', asistenList);
  console.log('Mahasiswa:', mahasiswaList);

  document.getElementById('detailStats').innerHTML = `
                <div class="stat-card">
                    <div class="stat-number">${jadwalList.length}</div>
                    <div class="stat-label">Total Jadwal</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${asistenList.length}</div>
                    <div class="stat-label">Total Asisten</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${mahasiswaList.length}</div>
                    <div class="stat-label">Total Mahasiswa</div>
                </div>
            `;

  if (jadwalList.length > 0) {
    document.getElementById('jadwalBody').innerHTML = jadwalList.map(j => `
                    <tr>
                        <td>${escapeHtml(j.hari || '-')}</td>
                        <td>${escapeHtml(j.jam_mulai || '-')}</td>
                        <td>${escapeHtml(j.jam_selesai || '-')}</td>
                        <td>${escapeHtml((j.laboratorium && j.laboratorium.nama_laboratorium) || '-')}</td>
                        <td><span class="status-badge ${j.status === 'Dibuka' || j.status === 'active' ? 'status-active' : 'status-inactive'}">${escapeHtml(j.status || 'Active')}</span></td>
                    </tr>
                `).join('');
  } else {
    document.getElementById('jadwalBody').innerHTML = '<tr><td colspan="5" style="text-align: center;">Belum ada jadwal</td></tr>';
  }

  if (asistenList.length > 0) {
    document.getElementById('asistenBody').innerHTML = asistenList.map(a => `
                    <tr>
                        <td>${escapeHtml(a.nomor_induk || a.nim || '-')}</td>
                        <td>${escapeHtml(a.nama || a.name || '-')}</td>
                        <td><span class="status-badge status-active">Active</span></td>
                    </tr>
                `).join('');
  } else {
    document.getElementById('asistenBody').innerHTML = '<tr><td colspan="3" style="text-align: center;">Belum ada asisten</td></tr>';
  }

  if (mahasiswaList.length > 0) {
    document.getElementById('mahasiswaBody').innerHTML = mahasiswaList.map(m => `
                    <tr>
                        <td>${escapeHtml(m.nomor_induk || m.nim || '-')}</td>
                        <td>${escapeHtml(m.nama || m.name || '-')}</td>
                    </tr>
                `).join('');
  } else {
    document.getElementById('mahasiswaBody').innerHTML = '<tr><td colspan="2" style="text-align: center;">Belum ada mahasiswa</td></tr>';
  }

  document.getElementById('kelolaJadwalBtn').href = "/admin/jadwal?praktikum_id=" + id;
  document.getElementById('alokasiAsistenBtn').href = "/admin/asisten/" + id;

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

window.onclick = function (event) {
  if (event.target.classList && event.target.classList.contains('modal')) {
    event.target.style.display = 'none';
  }
}

function closeDetail() {
  document.getElementById("detailModal").style.display = "none";
}
