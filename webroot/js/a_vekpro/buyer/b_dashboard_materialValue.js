var root4 = am5.Root.new("chartdiv4");
var myTheme4 = am5.Theme.new(root4);

myTheme4.rule("Grid", ["base"]).setAll({
  strokeOpacity: 0.1
});


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root4.setThemes([
  am5themes_Animated.new(root4),
  myTheme4
]);


// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart4 = root4.container.children.push(
  am5xy.XYChart.new(root4, {
    panX: false,
    panY: false,
    wheelX: "none",
    wheelY: "none",
    paddingLeft: 0
  })
);


// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var yRenderer4 = am5xy.AxisRendererY.new(root4, {
  minGridDistance: 30,
  minorGridEnabled: true
});
yRenderer4.grid.template.set("location", 1);

var yAxis4 = chart4.yAxes.push(
  am5xy.CategoryAxis.new(root4, {
    maxDeviation: 0,
    categoryField: "country",
    renderer: yRenderer4
  })
);

var xAxis4 = chart4.xAxes.push(
  am5xy.ValueAxis.new(root4, {
    maxDeviation: 0,
    min: 0,
    renderer: am5xy.AxisRendererX.new(root4, {
      visible: true,
      strokeOpacity: 0.1,
      minGridDistance: 80
    })
  })
);


// Create series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
var series4 = chart4.series.push(
  am5xy.ColumnSeries.new(root4, {
    name: "Series 1",
    xAxis: xAxis4,
    yAxis: yAxis4,
    valueXField: "value",
    sequencedInterpolation: true,
    categoryYField: "country"
  })
);

var columnTemplate4 = series4.columns.template;

columnTemplate4.setAll({
  draggable: true,
  cursorOverStyle: "pointer",
  tooltipText: "drag to rearrange",
  cornerRadiusBR: 10,
  cornerRadiusTR: 10,
  strokeOpacity: 0
});
var customColors = ["#F7941D", "#FFD580", "#FFE5B4"];

columnTemplate4.adapters.add("fill", function (fill, target) {
  var columnIndex = series4.columns.indexOf(target);
  return customColors[columnIndex % customColors.length];
});

columnTemplate4.adapters.add("stroke", (stroke, target) => {
  return chart4.get("colors").getIndex(series4.columns.indexOf(target));
});

columnTemplate4.events.on("dragstop", () => {
  sortCategoryAxis();
});

// Get series item by category
function getSeriesItem(category) {
  for (var i = 0; i < series4.dataItems.length; i++) {
    var dataItem4 = series4.dataItems[i];
    if (dataItem4.get("categoryY") == category) {
      return dataItem4;
    }
  }
}


// Axis sorting
function sortCategoryAxis() {
  // Sort by value
  series4.dataItems.sort(function (x, y) {
    return y.get("graphics").y() - x.get("graphics").y();
  });

  var easing = am5.ease.out(am5.ease.cubic);

  // Go through each axis item
  am5.array.each(yAxis4.dataItems, function (dataItem) {
    // get corresponding series item
    var seriesDataItem = getSeriesItem(dataItem.get("category"));

    if (seriesDataItem) {
      // get index of series data item
      var index = series4.dataItems.indexOf(seriesDataItem);

      var column = seriesDataItem.get("graphics");

      // position after sorting
      var fy =
        yRenderer4.positionToCoordinate(yAxis4.indexToPosition(index)) -
        column.height() / 2;

      // set index to be the same as series data item index
      if (index != dataItem.get("index")) {
        dataItem.set("index", index);

        // current position
        var x = column.x();
        var y = column.y();

        column.set("dy", -(fy - y));
        column.set("dx", x);

        column.animate({ key: "dy", to: 0, duration: 600, easing: easing });
        column.animate({ key: "dx", to: 0, duration: 600, easing: easing });
      } else {
        column.animate({ key: "y", to: fy, duration: 600, easing: easing });
        column.animate({ key: "x", to: 0, duration: 600, easing: easing });
      }
    }
  });

  // Sort axis items by index.
  // This changes the order instantly, but as dx and dy is set and animated,
  // they keep in the same places and then animate to true positions.
  yAxis4.dataItems.sort(function (x, y) {
    return x.get("index") - y.get("index");
  });
}

// Set data
var data4 = chartdiv4_data;

xAxis4.get("renderer").labels.template.setAll({ fontSize: 12 });
yAxis4.get("renderer").labels.template.setAll({ fontSize: 12 });

yAxis4.data.setAll(data4);
series4.data.setAll(data4);


// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
series4.appear(1000);
chart4.appear(1000, 100);
