"use strict";
document.addEventListener("DOMContentLoaded", function () {
    setTimeout(function () {
        fetch("/chart-data/invoices")
            .then((response) => response.json())
            .then((data) => {
                var options_invoice = {
                    chart: {
                        height: 350,
                        type: "line",
                        toolbar: { show: false },
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: "50%",
                        },
                    },
                    legend: { show: false },
                    stroke: {
                        width: [0, 2],
                        curve: "smooth",
                    },
                    dataLabels: { enabled: false },
                    series: [
                        {
                            name: "Total",
                            type: "column",
                            data: data.total,
                        },
                        {
                            name: "Paid",
                            type: "line",
                            data: data.paid,
                        },
                        {
                            name: "Partial Paid",
                            type: "line",
                            data: data.partial,
                        },
                        {
                            name: "Unpaid",
                            type: "line",
                            data: data.unpaid,
                        },
                    ],
                    tooltip: {
                        y: {
                            formatter: function (val, opts) {
                                const seriesIndex = opts.seriesIndex;
                                const dataIndex = opts.dataPointIndex;
                                switch (seriesIndex) {
                                    case 0:
                                        return data.tooltipTotal[dataIndex];
                                    case 1:
                                        return data.tooltipPaid[dataIndex];
                                    case 2:
                                        return data.tooltipPartial[dataIndex];
                                    case 3:
                                        return data.tooltipUnpaid[dataIndex];
                                    default:
                                        return val;
                                }
                            },
                        },
                    },
                    fill: {
                        type: "gradient",
                        gradient: {
                            inverseColors: false,
                            shade: "light",
                            type: "vertical",
                            opacityFrom: [0, 1],
                            opacityTo: [0.5, 1],
                            stops: [0, 100],
                            hover: {
                                inverseColors: false,
                                shade: "light",
                                type: "vertical",
                                opacityFrom: 0.15,
                                opacityTo: 0.65,
                                stops: [0, 96, 100],
                            },
                        },
                    },
                    markers: {
                        size: 3,
                        colors: "#fFF",
                        strokeColors: "#e58a00",
                        strokeWidth: 2,
                        shape: "circle",
                        hover: { size: 5 },
                    },
                    colors: ["#e58a00", "#e58a00"],
                    labels: data.months,
                    yaxis: {
                        tickAmount: 3,
                    },
                    grid: {
                        show: true,
                        borderColor: "#00000010",
                    },
                    xaxis: {
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        tickAmount: 11,
                    },
                };
                var chart = new ApexCharts(
                    document.querySelector("#invoice-chart"),
                    options_invoice
                );
                chart.render();
            });
    }, 500);
});
