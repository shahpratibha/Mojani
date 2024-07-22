// GeoServer URL
var geoserverUrl = "https://portal.geopulsea.com/geoserver/";

// Specific layer to show in the legend
var layerName = "Mojani:District_Boundary";

// Legend control
var legendControl = L.control({ position: "topright" });

legendControl.onAdd = function (map) {
  var div = L.DomUtil.create("div", "info legend legend-div minimized"); // Initially minimized
// Add collapsible button
var collapseButton = L.control({ position: "topright" });

collapseButton.onAdd = function (map) {
  var button = L.DomUtil.create("button", "collapse-button");
  button.innerHTML = "<i class='fa-solid fa-list' style='color:darkblue;'></i>"; // Initial text

  button.onclick = function () {
    var legendDiv = document.querySelector(".info.legend");
    if (legendDiv.style.display === "none" || legendDiv.classList.contains('minimized')) {
      legendDiv.style.display = "block";
      legendDiv.classList.remove('minimized');
    } else {
      legendDiv.style.display = "none";
      legendDiv.classList.add('minimized');
    }
  };

  return button;
};

collapseButton.addTo(map);

  // Function to fetch and populate the legend
  function updateLegend() {
    // Clear the existing legend
    div.innerHTML = '';

    var legendUrl =
      geoserverUrl +
      "/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=" +
      layerName;

    var layerDisplayName = layerName.split(":")[1]; // Extract display name

    div.innerHTML +=
      "<p><strong>" +
      layerDisplayName +
      "</strong></p>" +
      '<img src="' +
      legendUrl +
      '" alt="' +
      layerDisplayName +
      ' legend"><br>';
  }

  // Initially update the legend
  updateLegend();

  return div;
};

legendControl.addTo(map);


// North Image and scale
map.options.scale = true;
L.control.scale().addTo(map);

// Create a custom control for the north arrow
var northArrowControl = L.Control.extend({
  options: {
    position: "bottomleft",
  },

  onAdd: function (map) {
    var container = L.DomUtil.create("div", "leaflet-bar leaflet-control");
    container.innerHTML =
      '<img src="mainmodal/002-cardinal-point.png" class="north-arrow" alt="">';
    return container;
  },
});

map.addControl(new northArrowControl());
