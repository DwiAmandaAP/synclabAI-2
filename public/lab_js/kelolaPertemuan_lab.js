const pertemuanDataMap = new Map();

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.pertemuan-data').forEach(el => {
        const id = parseInt(el.dataset.id);
        pertemuanDataMap.set(id, {
            id: id,
            nama_pertemuan: el.dataset.nama_pertemuan,
            pertemuan_ke: el.dataset.pertemuan_ke,
            deskripsi_pertemuan: el.dataset.deskripsi_pertemuan,
            id_jadwal: el.dataset.id_jadwal,
            id_modul: el.dataset.id_modul,
            jadwal: JSON.parse(el.dataset.jadwal || 'null'),
            modul: JSON.parse(el.dataset.modul || 'null'),
            created_at: el.dataset.created_at,
            updated_at: el.dataset.updated_at
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
    const pertemuan = pertemuanDataMap.get(id);
    if (!pertemuan) {
        console.error('Pertemuan not found:', id);
        return;
    }

    document.getElementById('edit_nama_pertemuan').value = pertemuan.nama_pertemuan || '';
    document.getElementById('edit_pertemuan_ke').value = pertemuan.pertemuan_ke || '';
    document.getElementById('edit_deskripsi_pertemuan').value = pertemuan.deskripsi_pertemuan || '';

    if (pertemuan.id_jadwal && pertemuan.id_jadwal !== 'null' && pertemuan.id_jadwal !== '') {
        document.getElementById('edit_id_jadwal').value = pertemuan.id_jadwal;
    } else {
        document.getElementById('edit_id_jadwal').value = '';
    }

    if (pertemuan.id_modul && pertemuan.id_modul !== 'null' && pertemuan.id_modul !== '') {
        document.getElementById('edit_id_modul').value = pertemuan.id_modul;
    } else {
        document.getElementById('edit_id_modul').value = '';
    }

    document.getElementById('editModal').style.display = 'flex';
}

function openDetailModal(id) {
    const pertemuan = pertemuanDataMap.get(id);
    if (!pertemuan) return;

    let html = `
                <div class="detail-section">
                    <h4>Informasi Pertemuan</h4>
                    <table class="detail-info-table">
                        <tr><th>Nama Pertemuan</th><td>${escapeHtml(pertemuan.nama_pertemuan)}</td></tr>
                        <tr><th>Pertemuan Ke-</th><td>${escapeHtml(pertemuan.pertemuan_ke)}</td></tr>
                        <tr><th>Deskripsi</th><td>${escapeHtml(pertemuan.deskripsi_pertemuan || '-')}</td></tr>
                        <tr><th>Dibuat Pada</th><td>${formatDate(pertemuan.created_at)}</td></tr>
                        <tr><th>Terakhir Update</th><td>${formatDate(pertemuan.updated_at)}</td></tr>
                    </table>
                </div>
            `;

    if (pertemuan.jadwal) {
        html += `
                    <div class="detail-section">
                        <h4>📅 Jadwal Terkait</h4>
                        <table class="detail-info-table">
                            <tr><th>Hari</th><td>${escapeHtml(pertemuan.jadwal.hari || '-')}</td></tr>
                            <tr><th>Jam Mulai</th><td>${escapeHtml(pertemuan.jadwal.jam_mulai || '-')}</td></tr>
                            <tr><th>Jam Selesai</th><td>${escapeHtml(pertemuan.jadwal.jam_selesai || '-')}</td></tr>
                            <tr><th>Praktikum</th><td>${escapeHtml(pertemuan.jadwal.praktikum?.nama_praktikum || '-')}</td></tr>
                            <tr><th>Laboratorium</th><td>${escapeHtml(pertemuan.jadwal.laboratorium?.nama_laboratorium || '-')}</td></tr>
                        </table>
                    </div>
                `;
    } else {
        html += `
                    <div class="detail-section">
                        <h4>📅 Jadwal Terkait</h4>
                        <p style="padding: 15px; margin: 0; color: #666;">Tidak ada jadwal yang terkait</p>
                    </div>
                `;
    }

    if (pertemuan.modul) {
        html += `
                    <div class="detail-section">
                        <h4>📖 Modul Terkait</h4>
                        <table class="detail-info-table">
                            <tr><th>Judul Modul</th><td>${escapeHtml(pertemuan.modul.judul_modul || '-')}</td></tr>
                            <tr><th>Deskripsi Modul</th><td>${escapeHtml(pertemuan.modul.deskripsi_modul || '-')}</td></tr>
                            <tr><th>File Modul</th><td>${pertemuan.modul.file_modul ? `<a href="${pertemuan.modul.file_modul}" target="_blank">📄 Lihat File</a>` : '-'}</td></tr>
                        </table>
                    </div>
                `;
    } else {
        html += `
                    <div class="detail-section">
                        <h4>📖 Modul Terkait</h4>
                        <p style="padding: 15px; margin: 0; color: #666;">Tidak ada modul yang terkait</p>
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