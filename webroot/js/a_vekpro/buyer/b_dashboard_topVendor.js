// var chartdiv_data = [
//     { value: 10, category: "020 - Apar Export" },
//     { value: 9, category: "008 - Export" },
//     { value: 6, category: "007 - ENI" },
//     { value: 5, category: "006 - Escorts" },
//     { value: 4, category: "012 - SONALIKA" },
//     { value: 3, category: "009 - TAFE" },
//     { value: 1, category: "003 - RANE" },
// ]

function refresh_topVendor(data){ series.data.setAll(data); }

var root = am5.Root.new("chartdiv");
root.setThemes([am5themes_Animated.new(root)]);
var chart = root.container.children.push(am5percent.PieChart.new(root, { layout: root.verticalLayout }));
var series = chart.series.push(am5percent.PieSeries.new(root, { valueField: "value", categoryField: "category" }));
series.get("colors").set("colors", [
    am5.color(0xF4B678),
    am5.color(0xEF9234),
    am5.color(0xFFBF00),
    am5.color(0xFBCEB1),
    am5.color(0xFF7F50)
  ]);
series.labels.template.set("fontSize", 12);
refresh_topVendor(chartdiv_data)
series.appear(1000, 100);
