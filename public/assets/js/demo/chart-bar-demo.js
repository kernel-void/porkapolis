// Set default font and color - SB Admin 2 style
(Chart.defaults.global.defaultFontFamily = "Nunito"),
    '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#858796";

function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + "").replace(",", "").replace(" ", "");
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = typeof thousands_sep === "undefined" ? "." : thousands_sep,
        dec = typeof dec_point === "undefined" ? "," : dec_point,
        s = "",
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return "" + Math.round(n * k) / k;
        };
    s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || "").length < prec) {
        s[1] = s[1] || "";
        s[1] += new Array(prec - s[1].length + 1).join("0");
    }
    return s.join(dec);
}

// Ambil elemen canvas
var ctx = document.getElementById("myBarChart");

// Buat chart
var myBarChart = new Chart(ctx, {
    type: "bar",
    data: {
        labels: typeof chartLabels !== "undefined" ? chartLabels : [],
        datasets: [
            {
                label: "Pemasukan",
                backgroundColor: "#1cc88a",
                hoverBackgroundColor: "#17a673",
                borderColor: "#1cc88a",
                data: typeof dataPemasukan !== "undefined" ? dataPemasukan : [],
            },
            {
                label: "Pengeluaran",
                backgroundColor: "#36b9cc",
                hoverBackgroundColor: "#2c9faf",
                borderColor: "#36b9cc",
                data: typeof dataPengeluaran !== "undefined" ? dataPengeluaran : [],
            },
        ],
    },
    options: {
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 10,
                right: 25,
                top: 25,
                bottom: 0,
            },
        },
        scales: {
            xAxes: [
                {
                    time: {
                        unit: "month",
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false,
                    },
                    ticks: {
                        maxTicksLimit: 12,
                    },
                    maxBarThickness: 25,
                },
            ],
            yAxes: [
                {
                    ticks: {
                        min: 0,
                        maxTicksLimit: 6,
                        padding: 10,
                        callback: function (value) {
                            return "Rp" + number_format(value, 0, ",", ".");
                        },
                    },
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2],
                    },
                },
            ],
        },
        legend: {
            display: true,
        },
        tooltips: {
            titleMarginBottom: 10,
            titleFontColor: "#6e707e",
            titleFontSize: 14,
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: "#dddfeb",
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: true,
            caretPadding: 10,
            callbacks: {
                label: function (tooltipItem, chart) {
                    var datasetLabel =
                        chart.datasets[tooltipItem.datasetIndex].label || "";
                    return (
                        datasetLabel +
                        ": Rp" +
                        number_format(tooltipItem.yLabel, 0, ",", ".")
                    );
                },
            },
        },
    },
});

// Bar Full Screen
document
    .getElementById("fullscreenBar")
    .addEventListener("click", function () {
        const chartContainer = document.getElementById("chartBarContainer");

        // Tambahkan background putih
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

// Download Bar Charts via PNG
document.getElementById("downloadPNG").addEventListener("click", function () {
    const canvas = document.getElementById("myBarChart");
    const ctx = canvas.getContext("2d");

    // Buat canvas baru
    const newCanvas = document.createElement("canvas");
    const newCtx = newCanvas.getContext("2d");

    // Set ukuran sama dengan canvas asli
    newCanvas.width = canvas.width;
    newCanvas.height = canvas.height;

    // Gambar latar belakang putih
    newCtx.fillStyle = "#ffffff";
    newCtx.fillRect(0, 0, newCanvas.width, newCanvas.height);

    // Gambar chart di atas background putih
    newCtx.drawImage(canvas, 0, 0);

    // Generate URL dari canvas baru
    const url = newCanvas.toDataURL("image/png");

    // Buat link untuk download
    const a = document.createElement("a");
    a.href = url;
    a.download = "chart-bar.png";
    a.click();
});

// Download Bar Charts via JPEG
document.getElementById("downloadJPEG").addEventListener("click", function () {
    var canvas = document.getElementById("myBarChart");

    // Buat canvas baru dengan background putih
    var whiteCanvas = document.createElement("canvas");
    whiteCanvas.width = canvas.width;
    whiteCanvas.height = canvas.height;
    var ctx = whiteCanvas.getContext("2d");

    // Isi background dengan putih
    ctx.fillStyle = "#ffffff";
    ctx.fillRect(0, 0, whiteCanvas.width, whiteCanvas.height);

    // Gambar chart asli ke canvas baru
    ctx.drawImage(canvas, 0, 0);

    // Unduh sebagai JPEG
    var jpegUrl = whiteCanvas.toDataURL("image/jpeg", 1.0);
    var link = document.createElement("a");
    link.download = "bar-chart.jpeg";
    link.href = jpegUrl;
    link.click();
});

// Download Bars Charts via PDF
document.getElementById("downloadPDF").addEventListener("click", function () {
    const canvas = document.getElementById("myBarChart");

    // Buat canvas baru untuk latar putih + border
    const tempCanvas = document.createElement("canvas");
    const ctx = tempCanvas.getContext("2d");

    const originalWidth = canvas.width;
    const originalHeight = canvas.height;

    tempCanvas.width = originalWidth;
    tempCanvas.height = originalHeight;

    // Tambahkan latar putih
    ctx.fillStyle = "#ffffff";
    ctx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);

    // Tambahkan border
    ctx.strokeStyle = "#000000";
    ctx.lineWidth = 2;
    ctx.strokeRect(0, 0, tempCanvas.width, tempCanvas.height);

    // Gambar chart di atas latar
    ctx.drawImage(canvas, 0, 0);

    const imageData = tempCanvas.toDataURL("image/jpeg", 1.0);

    const { jsPDF } = window.jspdf;

    // A4 Landscape
    const pdf = new jsPDF({
        orientation: "landscape",
        unit: "mm",
        format: "a4",
    });

    const pageWidth = pdf.internal.pageSize.getWidth();
    const pageHeight = pdf.internal.pageSize.getHeight();

    // Hitung rasio ukuran gambar terhadap ukuran halaman PDF
    const imgProps = pdf.getImageProperties(imageData);
    const imgWidth = pageWidth - 20; // margin 10mm
    const imgHeight = (imgProps.height * imgWidth) / imgProps.width;

    const x = (pageWidth - imgWidth) / 2;
    const y = 20; // jarak dari atas halaman

    pdf.addImage(imageData, "JPEG", x, y, imgWidth, imgHeight);
    pdf.save("Bar-Chart.pdf");
});
