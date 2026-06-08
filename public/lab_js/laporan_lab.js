function toggleSidebar() {
  document.getElementById("sidebar").classList.toggle("show");
}

document.addEventListener("click", function(e) {
  const sidebar = document.getElementById("sidebar");
  const toggle = document.querySelector(".toggle");

  if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
    sidebar.classList.remove("active");
  }
});

let chartInstance = null;

function renderStats(data) {
  let tinggi = 0, rendah = 0, totalNilai = 0;

  data.forEach(d => {
    const nilai = hitungNilai(d);
    totalNilai += nilai;

    if (nilai >= 75) tinggi++;
    else rendah++;
  });

  const rata = Math.round(totalNilai / data.length);

  // masukin ke legend
  document.getElementById("valTinggi").innerText = tinggi;
  document.getElementById("valRendah").innerText = rendah;
  document.getElementById("valRata").innerText = rata;

  const ctx = document.getElementById("chart");
  if (!ctx) return;

  if (chartInstance) chartInstance.destroy();

  chartInstance = new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: ["Tinggi", "Rendah"],
      datasets: [{
        data: [tinggi, rendah],
        backgroundColor: [
          "#86efac", 
          "#fca5a5"  
        ],
        borderWidth: 0
      }]
    },
    options: {
      cutout: "65%",
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        }
      }
    }
  });
}

document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("export").addEventListener("change", function () {
    if (this.value === "pdf") {
      alert("Export PDF");
    } else if (this.value === "excel") {
      alert("Export Excel");
    }
    this.value = "";
  });
});