function record(matkul, pertemuan) {
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

