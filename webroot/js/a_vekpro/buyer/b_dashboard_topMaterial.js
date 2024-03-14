// var chartdiv2_data = [
//     { value: 10, category: "020 - Apar Export" },
//     { value: 9, category: "008 - Export" },
//     { value: 6, category: "007 - ENI" },
//     { value: 5, category: "006 - Escorts" },
//     { value: 4, category: "012 - SONALIKA" },
//     { value: 3, category: "009 - TAFE" },
//     { value: 1, category: "003 - RANE" },
// ]

function refresh_topMaterial(data){ series2.data.setAll(data); }

var root2 = am5.Root.new("chartdiv2");
root2.setThemes([am5themes_Animated.new(root2)]);
var chart2 = root2.container.children.push(am5percent.PieChart.new(root2, { layout: root2.verticalLayout }));
var series2 = chart2.series.push(am5percent.PieSeries.new(root2, { valueField: "value", categoryField: "category" }));
series2.get("colors").set("colors", [
  am5.color(0xF4B678),
  am5.color(0xEF9234),
  am5.color(0xFFBF00),
  am5.color(0xFBCEB1),
  am5.color(0xFF7F50)
  ]);
series2.labels.template.set("fontSize", 12);
refresh_topMaterial(chartdiv2_data)
series2.appear(1000, 100);
