function goDetail(matkul, pertemuan) {
  const url = `presensi_asisten.html?matkul=${encodeURIComponent(matkul)}&pertemuan=${pertemuan}`;
  window.location.href = url;
}

function goPage(page) {
  window.location.href = page;
}

function toggleDropdown(id) {
  const el = document.getElementById(id);
  el.classList.toggle("open");
}

function toggleSidebar() {
  const sidebar = document.querySelector(".sidebar");
  sidebar.classList.toggle("show");
}

function logout() {
  localStorage.removeItem("token"); 
  sessionStorage.clear(); 
  window.location.href = "login.html";
}

const params = new URLSearchParams(window.location.search);
const matkul = params.get("matkul");
const pertemuan = params.get("pertemuan");

const infoBox = document.querySelector(".info-box");

if (matkul && pertemuan) {
  infoBox.innerHTML = `
    <div>
      <p><b>Mata Kuliah :</b> ${matkul}</p>
      <p><b>Pertemuan :</b> ${pertemuan}</p>
    </div>
    <div>
      <p><b>Praktikum :</b> RPL</p>
      <p><b>Hari/Tanggal :</b> - </p>
    </div>
  `;

  loadPresensi(matkul, pertemuan);
} else {
  infoBox.innerHTML = `<p>Data tidak ditemukan</p>`;
}

function loadPresensi(matkul, pertemuan) {
  fetch(`http://localhost:8000/api/presensi?matkul=${encodeURIComponent(matkul)}&pertemuan=${pertemuan}`, {
    method: "GET",
    headers: {
      "Accept": "application/json"
    }
  })
    .then(res => res.json())
    .then(res => {
      renderPresensi(res.data || res);
    })
    .catch(() => {
      console.log("Gagal mengambil data presensi");
    });
}

function renderPresensi(data) {
  const tbody = document.querySelector("tbody");
  if (!tbody) return;

  tbody.innerHTML = "";

  data.forEach((item, index) => {
    tbody.innerHTML += `
      <tr>
        <td>${index + 1}</td>
        <td>${item.nim}</td>
        <td>${item.nama}</td>
        <td>${item.kelas}</td>
        <td>${item.status ?? "-"}</td>
      </tr>
    `;
  });
}

function setStatus(button, status) {
  const parent = button.parentElement;
  const allButtons = parent.querySelectorAll("button");

  allButtons.forEach(btn => btn.classList.remove("active"));
  button.classList.add("active");

  const row = button.closest("tr");
  const nim = row.children[0].innerText;

  fetch("http://localhost:8000/api/presensi/update-status", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json"
    },
    body: JSON.stringify({
      nim: nim,
      status: status,
      matkul: matkul,
      pertemuan: pertemuan
    })
  })
    .then(res => res.json())
    .then(res => {
      console.log("Status updated");
    })
    .catch(() => {
      console.log("Gagal update status");
    });
}

function saveAttendance() {
  const rows = document.querySelectorAll("tbody tr");

  let data = [];

  rows.forEach(row => {
    const nim = row.children[0].innerText;
    const nama = row.children[1].innerText;
    const kelas = row.children[2].innerText;

    const activeBtn = row.querySelector(".status-btn button.active");

    let status = null;

    if (activeBtn) {
      if (activeBtn.classList.contains("hadir")) status = "hadir";
      if (activeBtn.classList.contains("izin")) status = "izin";
      if (activeBtn.classList.contains("alpha")) status = "alpha";
    }

    data.push({
      nim,
      nama,
      kelas,
      status
    });
  });

  fetch("http://localhost:8000/api/presensi/save", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json"
    },
    body: JSON.stringify({
      matkul,
      pertemuan,
      data
    })
  })
    .then(res => res.json())
    .then(() => {
      alert("Presensi berhasil disimpan");
    })
    .catch(() => {
      alert("Gagal menyimpan presensi");
    });
}