"use strict";
document.addEventListener("DOMContentLoaded", function () {
    setTimeout(function () {
        fetch("/chart-data/course-report")
            .then((response) => response.json())
            .then((data) => {
                const months = data.months;

                let lastDataMonthIndex = -1;
                for (let i = data.months.length - 1; i >= 0; i--) {
                    if (
                        (data.teacher_data[i] ||
                            data.student_data[i] ||
                            data.employee_data[i]) > 0
                    ) {
                        lastDataMonthIndex = i;
                        break;
                    }
                }

                let reorderedMonths = months;
                let teacherData = data.teacher_data;
                let studentData = data.student_data;
                let employeeData = data.employee_data;

                if (lastDataMonthIndex !== -1) {
                    reorderedMonths = months
                        .slice(lastDataMonthIndex + 1)
                        .concat(months.slice(0, lastDataMonthIndex + 1));

                    teacherData = teacherData
                        .slice(lastDataMonthIndex + 1)
                        .concat(teacherData.slice(0, lastDataMonthIndex + 1));
                    studentData = studentData
                        .slice(lastDataMonthIndex + 1)
                        .concat(studentData.slice(0, lastDataMonthIndex + 1));
                    employeeData = employeeData
                        .slice(lastDataMonthIndex + 1)
                        .concat(employeeData.slice(0, lastDataMonthIndex + 1));
                }

                const chartOptions = {
                    chart: {
                        type: "bar",
                        height: 250,
                        toolbar: { show: false },
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
                    stroke: { show: true, width: 2, colors: ["transparent"] },
                    dataLabels: { enabled: false },
                    legend: {
                        position: "top",
                        horizontalAlign: "right",
                        fontFamily: `'Public Sans', sans-serif`,
                        offsetX: 10,
                        offsetY: 10,
                        itemMargin: { horizontal: 15, vertical: 5 },
                    },
                    colors: ["#4680ff", "#ffa21d", "#00cfe8"],
                    series: [
                        { name: "Teachers", data: teacherData },
                        { name: "Students", data: studentData },
                        { name: "Employees", data: employeeData },
                    ],
                    grid: { borderColor: "#00000010" },
                    yaxis: {
                        show: true,
                        min: 0,
                        max: Math.max(
                            ...studentData,
                            ...teacherData,
                            ...employeeData
                        ),
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

                const chartEl = document.querySelector(
                    "#course-report-bar-chart"
                );
                const chart = new ApexCharts(chartEl, chartOptions);
                chart.render();
            });
    }, 500);
});
