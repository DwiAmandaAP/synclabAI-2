function toggleDropdown(id) {
  document.getElementById(id).classList.toggle("open");
}

function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  sidebar.classList.toggle("active");
}

document.addEventListener("click", function (e) {
  const sidebar = document.getElementById("sidebar");
  const toggle = document.querySelector(".menu-toggle");

  if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
    sidebar.classList.remove("active");
  }
});

function logout() {
  localStorage.removeItem("token");
  sessionStorage.clear();
  window.location.href = "login.html";
}

function goPage(page) {
  window.location.href = page;
}

const searchInput = document.getElementById("searchInput");
const filterPraktikum = document.getElementById("filterPraktikum");
const filterKelas = document.getElementById("filterKelas");
const filterPertemuan = document.getElementById("filterPertemuan");
const filterMatkul = document.getElementById("filterMatkul");

function getEndpoint() {
  const path = window.location.pathname;

  if (path.includes("nilai_asisten")) return "/filter-nilai";
  if (path.includes("rekapNilai_asisten")) return "/filter-rekap";
  if (path.includes("mahasiswa_asisten")) return "/filter-mahasiswa";

  return "/filter-data";
}

function filterTable() {
  const search = searchInput?.value || "";
  const praktikum = filterPraktikum?.value || "";
  const kelas = filterKelas?.value || "";
  const pertemuan = filterPertemuan?.value || "";
  const matkul = filterMatkul?.value || "";

  const endpoint = getEndpoint();

  fetch(`${endpoint}?search=${search}&praktikum=${praktikum}&kelas=${kelas}&pertemuan=${pertemuan}&matkul=${matkul}`)
    .then(res => res.text())
    .then(html => {
      document.getElementById("tableBody").innerHTML = html;
    });
}

searchInput?.addEventListener("keyup", filterTable);
filterPraktikum?.addEventListener("change", filterTable);
filterKelas?.addEventListener("change", filterTable);
filterPertemuan?.addEventListener("change", filterTable);
filterMatkul?.addEventListener("change", filterTable);

document.getElementById("tableBody")?.addEventListener("click", function(e){
  if(e.target.classList.contains("delete")){
    e.target.closest("tr").remove();
  }

  if(e.target.classList.contains("save")){
    const row = e.target.closest("tr");
    alert("Data disimpan: " + row.cells[0].innerText);
  }
});