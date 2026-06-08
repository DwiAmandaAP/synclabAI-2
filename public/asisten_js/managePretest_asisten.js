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

let currentRow = null;

function openPopup(mode, row = null) {
  document.getElementById("pretestPopup").style.display = "flex";
  currentRow = row;

  document.getElementById("popupTitle").innerText =
    mode === "add" ? "Tambah Pretest" : "Edit Pretest";

  if (row) {
    const cells = row.querySelectorAll("td");

    p_nama.value = cells[0].innerText;
    p_praktikum.value = cells[1].innerText;
    p_pertemuan.value = cells[2].innerText;
    p_deskripsi.value = cells[4].innerText;
  } else {
    document.querySelectorAll("#pretestPopup input").forEach(i => i.value = "");
  }
}

function closePopup() {
  document.getElementById("pretestPopup").style.display = "none";
}

function hitungDurasi(mulai, akhir) {
  const [jam1, menit1] = mulai.split(":");
  const [jam2, menit2] = akhir.split(":");

  const start = parseInt(jam1) * 60 + parseInt(menit1);
  const end = parseInt(jam2) * 60 + parseInt(menit2);

  const durasi = end - start;

  return durasi + " menit";
}

document.getElementById("btnSave").onclick = function () {

  const durasi = hitungDurasi(p_mulai.value, p_akhir.value);

  const data = [
    p_nama.value,
    p_praktikum.value,
    p_pertemuan.value,
    durasi,
    p_deskripsi.value
  ];

  if (currentRow) {
    const cells = currentRow.querySelectorAll("td");
    data.forEach((val, i) => cells[i].innerText = val);
  } else {
    const row = document.createElement("tr");

    row.innerHTML = `
      <td>${data[0]}</td>
      <td>${data[1]}</td>
      <td>${data[2]}</td>
      <td>${data[3]}</td>
      <td>${data[4]}</td>
      <td class="actions-btn">
        <button class="edit">✏️</button>
        <button class="delete">🗑️</button>
      </td>
    `;

    document.querySelector("tbody").appendChild(row);
    attachEvents();
  }

  closePopup();
};

function deleteRow(btn) {
  if (confirm("Yakin hapus?")) {
    btn.closest("tr").remove();
  }
}

function attachEvents() {
  document.querySelectorAll(".edit").forEach(btn => {
    btn.onclick = function () {
      openPopup("edit", this.closest("tr"));
    };
  });

  document.querySelectorAll(".delete").forEach(btn => {
    btn.onclick = function () {
      deleteRow(this);
    };
  });
}

attachEvents();