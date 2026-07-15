document.addEventListener("DOMContentLoaded", function () {
    if (typeof pieChartData === "undefined") {
        console.error("pieChartData is undefined!");
        return;
    }

    const pieData = pieChartData;
    const labels = pieData.map((item) => item.kelas ?? "Unknown");
    const data = pieData.map((item) => item.belum_bayar ?? 0);

    const ctx = document.getElementById("myPieChart");
    const myPieChart = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: labels,
            datasets: [
                {
                    data: data,
                    backgroundColor: ["#4e73df", "#1cc88a", "#36b9cc", "#f6c23e", "#858796", "#fd7e14", "#e74a3b", 
                    "#20c9a6", "#6f42c1", "#f97316", "#f43f5e", "#0dcaf0", "#b089ec", "#64748b", "#38bdf8"],
                    hoverBackgroundColor: ["#2e59d9", "#17a673", "#2c9faf", "#dda20a", "#6c6f7c", "#e46300", "#c43b2f",
                    "#17a689", "#5a32a3", "#ea580c", "#be123c", "#0aa2c0", "#8b5cf6", "#475569", "#0ea5e9"],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: "#dddfeb",
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false,
            },
            cutoutPercentage: 80,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return `${context.label}: ${context.parsed} siswa belum bayar`;
                        },
                    },
                },
            },
        },
    });

    // Label bawah pie
    const legendDiv = document.getElementById("pieLabels");
    if (legendDiv) {
        const colors = ["#4e73df", "#1cc88a", "#36b9cc", "#f6c23e", "#858796", "#fd7e14", "#e74a3b",
                        "#20c9a6", "#6f42c1", "#f97316", "#f43f5e", "#0dcaf0", "#b089ec", "#64748b", "#38bdf8"];
        legendDiv.innerHTML = "";
        pieData.forEach((item, index) => {
            legendDiv.innerHTML += `
              <span class="mr-2">
                  <i class="fas fa-circle" style="color:${
                      colors[index % colors.length]
                  }"></i> ${item.kelas}
              </span>
          `;
        });
    }
});


// Full Screen Pie Chart
document.getElementById("fullscreenPie").addEventListener("click", function () {
    const chartContainer = document.getElementById("chartPieContainer");

    // Tambahkan background putih agar tampil bagus saat fullscreen
    chartContainer.style.backgroundColor = "#ffffff";

    if (chartContainer.requestFullscreen) {
        chartContainer.requestFullscreen();
    } else if (chartContainer.webkitRequestFullscreen) {
        // Safari
        chartContainer.webkitRequestFullscreen();
    } else if (chartContainer.msRequestFullscreen) {
        // IE11
        chartContainer.msRequestFullscreen();
    }
});


