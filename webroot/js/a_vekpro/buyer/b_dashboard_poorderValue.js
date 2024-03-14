var root3 = am5.Root.new("chartdiv3");
root3.setThemes([am5themes_Animated.new(root3)]);
var chart3 = root3.container.children.push(am5xy.XYChart.new(root3, {
  panX: false,
  panY: false,
  wheelX: "none",
  wheelY: "none",
  paddingLeft: 0
}));

chart3.zoomOutButton.set("forceHidden", true);
var yRenderer3 = am5xy.AxisRendererY.new(root3, {
  minGridDistance: 30,
  minorGridEnabled: true
});

yRenderer3.grid.template.set("location", 1);

var yAxis3 = chart3.yAxes.push(am5xy.CategoryAxis.new(root3, {
  maxDeviation: 0,
  categoryField: "network",
  renderer: yRenderer3,
  tooltip: am5.Tooltip.new(root3, { themeTags: ["axis"] })
}));

var xAxis3 = chart3.xAxes.push(am5xy.ValueAxis.new(root3, {
  maxDeviation: 0,
  min: 0,
  numberFormatter: am5.NumberFormatter.new(root3, {
    "numberFormat": "#,###a"
  }),
  extraMax: 0.1,
  renderer: am5xy.AxisRendererX.new(root3, {
    strokeOpacity: 0.1,
    minGridDistance: 80

  })
}));

series3 = chart3.series.push(am5xy.ColumnSeries.new(root3, {
  name: "Series 3",
  xAxis: xAxis3,
  yAxis: yAxis3,
  valueXField: "value",
  categoryYField: "network",
  tooltip: am5.Tooltip.new(root3, {
    pointerOrientation: "left",
    labelText: "{valueX}"
  })
}));

// Rounded corners for columns
series3.columns.template.setAll({
  cornerRadiusTR: 5,
  cornerRadiusBR: 5,
  strokeOpacity: 0
});

// Make each column to be of a different color
var customColors = ["#F7941D", "#F4BB44", "#FFE5B4"];

series3.columns.template.adapters.add("fill", function (fill, target) {
  var columnIndex = series3.columns.indexOf(target);
  return customColors[columnIndex % customColors.length];
});

series3.columns.template.adapters.add("stroke", function (stroke, target) {
  return chart3.get("colors").getIndex(series3.columns.indexOf(target));
});


// Set data
data3 = chartdiv3_data;

yAxis3.data.setAll(data3);
series3.data.setAll(data3);
sortCategoryAxis();

// Get series item by category
function getSeriesItem(category) {
  for (var i = 0; i < series3.dataItems.length; i++) {
    var dataItem = series3.dataItems[i];
    if (dataItem.get("categoryY") == category) {
      return dataItem;
    }
  }
}

chart3.set("cursor", am5xy.XYCursor.new(root3, {
  behavior: "none",
  xAxis: xAxis3,
  yAxis: yAxis3
}));


// Axis sorting
function sortCategoryAxis() {

  // Sort by value
  series3.dataItems.sort(function (x, y) {
    return x.get("valueX") - y.get("valueX"); // descending
    //return y.get("valueY") - x.get("valueX"); // ascending
  })

  // Go through each axis item
  am5.array.each(yAxis3.dataItems, function (dataItem) {
    // get corresponding series item
    var seriesDataItem3 = getSeriesItem(dataItem.get("category"));

    if (seriesDataItem3) {
      // get index of series data item
      var index3 = series3.dataItems.indexOf(seriesDataItem3);
      // calculate delta position
      var deltaPosition3 = (index3 - dataItem.get("index", 0)) / series3.dataItems.length;
      // set index to be the same as series data item index
      dataItem.set("index", index3);
      // set deltaPosition instanlty
      dataItem.set("deltaPosition", -deltaPosition3);
      // animate delta position to 0
      dataItem.animate({
        key: "deltaPosition",
        to: 0,
        duration: 1000,
        easing: am5.ease.out(am5.ease.cubic)
      })
    }
  });

  // Sort axis items by index.
  // This changes the order instantly, but as deltaPosition is set,
  // they keep in the same places and then animate to true positions.
  yAxis3.dataItems.sort(function (x, y) {
    return x.get("index") - y.get("index");
  });
}


xAxis3.get("renderer").labels.template.setAll({ fontSize: 12 });
yAxis3.get("renderer").labels.template.setAll({ fontSize: 12 });

function updateData() {
  am5.array.each(series3.dataItems, function (dataItem) {
    var value3 = dataItem.get("valueX") + Math.round(Math.random() * 1000000000 - 500000000);
    if (value3 < 0) {
      value3 = 500000000;
    }
    // both valueY and workingValueY should be changed, we only animate workingValueY
    dataItem.set("valueX", value3);
    dataItem.animate({
      key: "valueXWorking",
      to: value3,
      duration: 600,
      easing: am5.ease.out(am5.ease.cubic)
    });
  })

  sortCategoryAxis();
}

series3.appear(1000);
chart3.appear(1000, 100);
