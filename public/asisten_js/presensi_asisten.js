function goPage(page) {
  window.location.href = page;
}

const menuItems = document.querySelectorAll(".menu > li");

menuItems.forEach(item => {
  item.addEventListener("click", function () {
    if (this.classList.contains("dropdown")) return;

    menuItems.forEach(i => i.classList.remove("active"));
    this.classList.add("active");
  });
});

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

function setStatus(button, status) {
  const parent = button.parentElement;
  const allButtons = parent.querySelectorAll("button");

  allButtons.forEach(btn => btn.classList.remove("active"));
  button.classList.add("active");

  const row = button.closest("tr");
  const nim = row.children[0].innerText;

  console.log("NIM:", nim, "| Status:", status);
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
      nim: nim,
      nama: nama,
      kelas: kelas,
      status: status
    });
  });

  fetch("http://localhost:8000/api/attendance/save", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json"
    },
    body: JSON.stringify({
      meeting: 5,
      course: "Pemrograman Web",
      data: data
    })
  })
  .then(res => res.json())
  .then(res => {
    alert("Attendance berhasil disimpan");
  })
  .catch(err => {
    alert("Gagal menyimpan data");
  });
}