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

document.addEventListener("DOMContentLoaded", function () {

  let currentRow = null;

  function openPopup(mode, row = null) {
    const popup = document.getElementById("modulePopup");
    const title = document.getElementById("popupTitle");
    const inputs = document.querySelectorAll("#modulePopup input");

    const btnSave = document.getElementById("btnSave");
    const btnEdit = document.getElementById("btnEdit");

    popup.style.display = "flex";

    inputs.forEach(i => {
      i.value = "";
      i.disabled = false;
    });

    currentRow = row;

    if (mode === "add") {
      title.innerText = "Tambah Modul";
      btnSave.style.display = "inline-block";
      btnEdit.style.display = "none";

      document.querySelector(".btn-cancel").style.display = "inline-block";
    }

    if (mode === "detail") {
      title.innerText = "Detail Modul";
      fillForm(row);
      inputs.forEach(i => i.disabled = true);
      btnSave.style.display = "none";
      btnEdit.style.display = "none";

      document.querySelector(".btn-cancel").style.display = "none";
    }

    if (mode === "edit") {
      title.innerText = "Edit Modul";
      fillForm(row);
      btnSave.style.display = "inline-block";
      btnEdit.style.display = "none";

      document.querySelector(".btn-cancel").style.display = "inline-block";
    }
  }

  window.openPopup = openPopup; 

  function closePopup() {
    document.getElementById("modulePopup").style.display = "none";
  }

  window.closePopup = closePopup;

  function fillForm(row) {
    const cells = row.querySelectorAll("td");

    p_pertemuan.value = cells[0].innerText;
    p_praktikum.value = cells[1].innerText;
    p_deskripsi.value = cells[2].innerText;
    p_modul.value = cells[3].innerText;
    p_descModul.value = cells[4].innerText;
  }

  document.getElementById("btnSave").onclick = function () {
    const data = [
      p_pertemuan.value,
      p_praktikum.value,
      p_deskripsi.value,
      p_modul.value,
      p_descModul.value
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
        <td>-</td>
        <td class="actions-btn">
          <button class="edit">✏️</button>
          <button class="delete">🗑️</button>
          <button class="detail">🔍</button>
        </td>
      `;

      document.querySelector("tbody").appendChild(row);
      attachEvents();
    }

    closePopup();
  };

  function deleteRow(btn) {
    if (confirm("Yakin mau hapus data ini?")) {
      btn.closest("tr").remove();
    }
  }

  function attachEvents() {
    document.querySelectorAll(".detail").forEach(btn => {
      btn.onclick = function () {
        openPopup("detail", this.closest("tr"));
      };
    });

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

});