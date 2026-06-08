function toggleSidebar() {
  document.getElementById("sidebar").classList.toggle("active");
}

function toggleDropdown(id) {
  const menu = document.getElementById(id);
  menu.classList.toggle("open");
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

let currentDate = new Date();

function renderCalendar() {
  const monthYear = document.getElementById("monthYear");
  const calendarDates = document.getElementById("calendarDates");

  const year = currentDate.getFullYear();
  const month = currentDate.getMonth();

  const firstDay = new Date(year, month, 1).getDay();
  const lastDate = new Date(year, month + 1, 0).getDate();

  const monthNames = [
    "Januari","Februari","Maret","April","Mei","Juni",
    "Juli","Agustus","September","Oktober","November","Desember"
  ];

  monthYear.innerText = `${monthNames[month]} ${year}`;
  calendarDates.innerHTML = "";

  let start = firstDay === 0 ? 6 : firstDay - 1;

  for (let i = 0; i < start; i++) {
    calendarDates.innerHTML += `<div></div>`;
  }

  const today = new Date();

  for (let i = 1; i <= lastDate; i++) {
    let isToday =
      i === today.getDate() &&
      month === today.getMonth() &&
      year === today.getFullYear();

    calendarDates.innerHTML += `
      <div class="${isToday ? "today" : ""}">
        ${i}
      </div>
    `;
  }
}

function prevMonth() {
  currentDate.setMonth(currentDate.getMonth() - 1);
  renderCalendar();
}

function nextMonth() {
  currentDate.setMonth(currentDate.getMonth() + 1);
  renderCalendar();
}

// Data loaded dynamically from server via blade @json
let praktikums = [];

const colors = ["pink", "blue", "green", "yellow"];

function showDetail(index) {
  const pills = document.querySelectorAll(".pill");
  pills.forEach(p => p.classList.remove("active"));
  pills[index].classList.add("active");

  const box = document.getElementById("detailBox");
  box.className = "attendance-detail " + colors[index];

  const d = data[index];

  box.innerHTML = `
    <div class="detail-title">${d.title}</div>
    <div class="detail-item"><b>Practicum:</b> ${d.practicum}</div>
    <div class="detail-item"><b>Ruangan:</b> ${d.room}</div>
    <div class="detail-item"><b>Hari/Tgl:</b> ${d.date}</div>
    <div class="detail-item"><b>Jam:</b> ${d.time}</div>
    <div class="detail-item"><b>Jumlah:</b> ${d.total}</div>
    <div class="input-btn" onclick="goPage('presensi_asisten.html')">Input</div>
  `;
}

document.addEventListener("DOMContentLoaded", function () {
  renderCalendar();
  showDetail(0);
});