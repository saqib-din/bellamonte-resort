"use strict";
document.addEventListener("DOMContentLoaded", function () {
    setTimeout(function () {
        fetch("/api/payroll-report")
            .then((response) => response.json())
            .then((data) => {
                const months = data.months;

                // last data month ka index nikal lo
                let lastDataMonthIndex = -1;
                for (let i = data.months.length - 1; i >= 0; i--) {
                    if (data.paid_data[i] || data.unpaid_data[i]) {
                        lastDataMonthIndex = i;
                        break;
                    }
                }

                let reorderedMonths = months;
                let paidData = data.paid_data;
                let unpaidData = data.unpaid_data;

                if (lastDataMonthIndex !== -1) {
                    reorderedMonths = months
                        .slice(lastDataMonthIndex + 1)
                        .concat(months.slice(0, lastDataMonthIndex + 1));

                    paidData = paidData
                        .slice(lastDataMonthIndex + 1)
                        .concat(paidData.slice(0, lastDataMonthIndex + 1));
                    unpaidData = unpaidData
                        .slice(lastDataMonthIndex + 1)
                        .concat(unpaidData.slice(0, lastDataMonthIndex + 1));
                }

                const chartOptions = {
                    chart: {
                        type: "bar",
                        height: 250,
                        toolbar: { show: false }, // toolbar hide
                        zoom: {
                            enabled: true,
                            type: "x",
                            autoScaleYaxis: true,
                        },
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: "60%",
                            borderRadius: 4,
                        },
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ["transparent"],
                    },
                    dataLabels: { enabled: false },
                    legend: {
                        position: "top",
                        horizontalAlign: "right",
                        fontFamily: `'Public Sans', sans-serif`,
                        offsetX: 10,
                        offsetY: 10,
                        itemMargin: { horizontal: 15, vertical: 5 },
                    },
                    colors: ["#00825f", "#dc2626"],
                    series: [
                        { name: "Paid", data: paidData },
                        { name: "Unpaid", data: unpaidData },
                    ],
                    grid: { borderColor: "#00000010" },
                    yaxis: {
                        show: true,
                        min: 0,
                        max: Math.max(...paidData, ...unpaidData),
                        tickAmount: 5,
                        labels: {
                            style: {
                                fontFamily: `'Public Sans', sans-serif`,
                                fontSize: "12px",
                            },
                        },
                    },
                    xaxis: {
                        categories: reorderedMonths,
                        labels: {
                            style: {
                                fontFamily: `'Public Sans', sans-serif`,
                                fontSize: "12px",
                            },
                        },
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return `${val} total`;
                            },
                        },
                    },
                };

                const chart = new ApexCharts(
                    document.querySelector("#payroll-report-bar-chart"),
                    chartOptions
                );
                chart.render();
            })
            .catch((error) =>
                console.error("Error fetching payroll data:", error)
            );
    }, 500);
});
