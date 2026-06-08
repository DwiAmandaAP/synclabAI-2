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
const filterPertemuan = document.getElementById("filterPertemuan");
const filterStatus = document.getElementById("filterStatus");
const filterMatkul = document.getElementById("filterMatkul");

searchInput.style.width = "180px";
filterPraktikum.style.width = "180px";
filterPertemuan.style.width = "180px";
filterStatus.style.width = "140px";
filterMatkul.style.width = "140px";

const rows = document.querySelectorAll("#tableBody tr");
const modal = document.getElementById("reviewModal");

function filterTable() {
  const search = searchInput.value.toLowerCase();
  const praktikum = filterPraktikum.value;
  const pertemuan = filterPertemuan.value;
  const status = filterStatus.value.toLowerCase();
  const matkul = filterMatkul.value;

  rows.forEach(row => {
    const nama = row.cells[0].innerText.toLowerCase();
    const nim = row.cells[1].innerText.toLowerCase();
    const pr = row.cells[2].innerText;
    const pt = row.cells[3].innerText;
    const st = row.cells[5].innerText.toLowerCase();

    const matchSearch = nama.includes(search) || nim.includes(search);
    const matchMatkul = !matkul || pr === matkul;
    const matchPraktikum = !praktikum || pr === praktikum;
    const matchPertemuan = !pertemuan || pt === pertemuan;
    const matchStatus = !status || st === status;

    row.style.display =
      (matchSearch && matchMatkul && matchPraktikum && matchPertemuan && matchStatus)
        ? ""
        : "none";
  });
}

searchInput.addEventListener("keyup", filterTable);
filterPraktikum.addEventListener("change", filterTable);
filterPertemuan.addEventListener("change", filterTable);
filterStatus.addEventListener("change", filterTable);
filterMatkul.addEventListener("change", filterTable);

document.getElementById("tableBody").addEventListener("click", function (e) {
  if (e.target.classList.contains("detail")) {
    const row = e.target.closest("tr");
    openModal(row);
  }
});

function openModal(row) {
  modal.style.display = "flex";

  document.getElementById("mNama").innerText = row.cells[0].innerText;
  document.getElementById("mNim").innerText = row.cells[1].innerText;
  document.getElementById("mPraktikum").innerText = row.cells[2].innerText;
  document.getElementById("mPertemuan").innerText = row.cells[3].innerText;
  document.getElementById("mTanggal").innerText = row.cells[4].innerText;
}

function closeModal() {
  modal.style.display = "none";
}

window.addEventListener("click", function (e) {
  if (e.target === modal) {
    closeModal();
  }
});

document.querySelector(".btn-save").addEventListener("click", function () {
  const komentar = document.getElementById("komentar").value;
  const nilai = document.getElementById("nilai").value;
  const status = document.getElementById("status").value;

  alert(
    "Data tersimpan:\n" +
    "Komentar: " + komentar + "\n" +
    "Nilai: " + nilai + "\n" +
    "Status: " + status
  );

  closeModal();
});