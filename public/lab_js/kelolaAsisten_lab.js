const userDataMap = new Map();

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.user-data').forEach(el => {
        const id = parseInt(el.dataset.id);
        userDataMap.set(id, {
            id: id,
            nomor_induk: el.dataset.nomor_induk,
            nama: el.dataset.nama,
            email: el.dataset.email,
            role: el.dataset.role,
            nohp: el.dataset.nohp || '-',
            status: el.dataset.status || 'active',
            created_at: el.dataset.created_at,
            updated_at: el.dataset.updated_at,
            praktikums: JSON.parse(el.dataset.praktikums || '[]'),
            jadwals: JSON.parse(el.dataset.jadwals || '[]')
        });
    });
});

function openEditModal(id) {
    const user = userDataMap.get(id);
    if (!user) {
        console.error('User not found:', id);
        return;
    }
    
    document.getElementById('edit_nomor_induk').value = user.nomor_induk;
    document.getElementById('edit_nama').value = user.nama;
    document.getElementById('edit_email').value = user.email;
    document.getElementById('edit_nohp').value = user.nohp !== '-' ? user.nohp : '';
    const roleSelect = document.getElementById('edit_role');
    if (roleSelect && user.role) {
        const userRole = user.role.toLowerCase();
        let optionFound = false;
        for (let i = 0; i < roleSelect.options.length; i++) {
            if (roleSelect.options[i].value.toLowerCase() === userRole) {
                roleSelect.selectedIndex = i;
                optionFound = true;
                break;
            }
        }
    }
    
    const statusSelect = document.getElementById('edit_status');
    if (statusSelect && user.status) {
        const userStatus = user.status.toLowerCase();
        for (let i = 0; i < statusSelect.options.length; i++) {
            if (statusSelect.options[i].value.toLowerCase() === userStatus) {
                statusSelect.selectedIndex = i;
                break;
            }
        }
    }
    document.getElementById('edit_password').value = '';
    document.getElementById('editModal').style.display = 'flex';
}

function openDetailModal(id) {
    const user = userDataMap.get(id);
    if (!user) {
        console.error('User not found:', id);
        return;
    }
    
    let html = `
        <div class="detail-section">
            <h4>Informasi Pengguna</h4>
            <table>
                <tr>
                    <th>Nomor Induk</th>
                    <td>${escapeHtml(user.nomor_induk)}</td>
                </tr>
                <tr>
                    <th>Nama Lengkap</th>
                    <td>${escapeHtml(user.nama)}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>${escapeHtml(user.email)}</td>
                </tr>
                <tr>
                    <th>No. HP</th>
                    <td>${escapeHtml(user.nohp)}</td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td><span class="role-badge role-${user.role}">${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</span></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><span class="badge badge-${user.status}">${user.status === 'active' ? 'Active' : 'Inactive'}</span></td>
                </tr>
                <tr>
                    <th>Terdaftar Sejak</th>
                    <td>${formatDate(user.created_at)}</td>
                </tr>
                <tr>
                    <th>Terakhir Update</th>
                    <td>${formatDate(user.updated_at)}</td>
                </tr>
            </table>
        </div>
    `;
    
    if (user.praktikums && user.praktikums.length > 0) {
        html += `
            <div class="detail-section">
                <h4>📚 Praktikum</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Kode Praktikum</th>
                            <th>Nama Praktikum</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        user.praktikums.forEach(praktikum => {
            html += `
                <tr>
                    <td>${escapeHtml(praktikum.kode_praktikum)}</td>
                    <td>${escapeHtml(praktikum.nama_praktikum)}</td>
                    <td><span class="badge badge-${praktikum.pivot_status}">${praktikum.pivot_status}</span></td>
                </tr>
            `;
        });
        html += `</tbody></table></div>`;
    } else {
        html += `
            <div class="detail-section">
                <h4>📚 Praktikum</h4>
                <p style="padding: 15px; margin: 0; color: #666;">Belum ada praktikum</p>
            </div>
        `;
    }
    
    // Jadwal Section
    if (user.jadwals && user.jadwals.length > 0) {
        html += `
            <div class="detail-section">
                <h4>📅 Jadwal</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Praktikum</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        user.jadwals.forEach(jadwal => {
            html += `
                <tr>
                    <td>${escapeHtml(jadwal.hari)}</td>
                    <td>${escapeHtml(jadwal.jam_mulai)}</td>
                    <td>${escapeHtml(jadwal.jam_selesai)}</td>
                    <td>${escapeHtml(jadwal.praktikum_nama)}</td>
                </tr>
            `;
        });
        html += `</tbody></table></div>`;
    } else {
        html += `
            <div class="detail-section">
                <h4>📅 Jadwal</h4>
                <p style="padding: 15px; margin: 0; color: #666;">Belum ada jadwal</p>
            </div>
        `;
    }
    
    document.getElementById('detailContent').innerHTML = html;
    document.getElementById('detailModal').style.display = 'flex';
}

// Helper function to escape HTML special characters
function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// Helper function to format date
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

// Close modal functions (in case needed globally)
function closeModal() {
    document.getElementById('addModal').style.display = 'none';
    document.getElementById('editModal').style.display = 'none';
}

function closeDetail() {
    document.getElementById('detailModal').style.display = 'none';
}