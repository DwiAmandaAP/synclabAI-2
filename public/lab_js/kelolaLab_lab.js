// Store all laboratorium data
const laboratoriumDataMap = new Map();

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.laboratorium-data').forEach(el => {
        const id = parseInt(el.dataset.id);
        laboratoriumDataMap.set(id, {
            id: id,
            kode_laboratorium: el.dataset.kode_laboratorium,
            nama_laboratorium: el.dataset.nama_laboratorium,
            lokasi: el.dataset.lokasi,
            kapasitas: el.dataset.kapasitas,
            id_kepala_lab: el.dataset.id_kepala_lab,
            kepala_lab_nama: el.dataset.kepala_lab_nama,
            status: el.dataset.status,
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
    const lab = laboratoriumDataMap.get(id);
    if (!lab) {
        console.error('Laboratorium not found:', id);
        return;
    }
    document.getElementById('edit_kode_laboratorium').value = lab.kode_laboratorium || '';
    document.getElementById('edit_nama_laboratorium').value = lab.nama_laboratorium || '';
    document.getElementById('edit_lokasi').value = lab.lokasi || '';
    document.getElementById('edit_kapasitas').value = lab.kapasitas || '';

    if (lab.id_kepala_lab && lab.id_kepala_lab !== 'null' && lab.id_kepala_lab !== '') {
        document.getElementById('edit_id_kepala_lab').value = lab.id_kepala_lab;
    } else {
        document.getElementById('edit_id_kepala_lab').value = '';
    }

    if (lab.status) {
        document.getElementById('edit_status').value = lab.status;
    } else {
        document.getElementById('edit_status').value = 'Tersedia';
    }

    document.getElementById('editModal').style.display = 'flex';
}

function openDetailModal(id) {
    const lab = laboratoriumDataMap.get(id);
    if (!lab) return;

    let html = `
                <div class="detail-section">
                    <h4>Informasi Laboratorium</h4>
                    <table>
                        <tr><th>Kode Laboratorium</th><td>${escapeHtml(lab.kode_laboratorium)}</td></tr>
                        <tr><th>Nama Laboratorium</th><td>${escapeHtml(lab.nama_laboratorium)}</td></tr>
                        <tr><th>Lokasi</th><td>${escapeHtml(lab.lokasi || '-')}</td></tr>
                        <tr><th>Kapasitas</th><td>${escapeHtml(lab.kapasitas || '-')} Orang</td></tr>
                        <tr><th>Kepala Laboratorium</th><td>${escapeHtml(lab.kepala_lab_nama || '-')}</td></tr>
                        <tr><th>Status</th><td><span class="badge badge-${lab.status === 'Tersedia' ? 'success' : 'warning'}">${escapeHtml(lab.status || 'Tersedia')}</span></td></tr>
                        <tr><th>Dibuat Pada</th><td>${formatDate(lab.created_at)}</td></tr>
                        <tr><th>Terakhir Update</th><td>${formatDate(lab.updated_at)}</td></tr>
                    </table>
                </div>
            `;

    if (lab.status === 'Terpakai') {
        html += `
                    <div class="detail-section">
                        <h4>📊 Informasi Penggunaan</h4>
                        <p style="padding: 15px; margin: 0; color: #666;">
                            <i class="fas fa-info-circle"></i> Laboratorium sedang digunakan untuk kegiatan praktikum.
                        </p>
                    </div>
                `;
    } else {
        html += `
                    <div class="detail-section">
                        <h4>✅ Status Ketersediaan</h4>
                        <p style="padding: 15px; margin: 0; color: #666;">
                            <i class="fas fa-check-circle"></i> Laboratorium tersedia dan siap digunakan.
                        </p>
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