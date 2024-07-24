var districtCache = {};
var geoURL = "https://info.dpzoning.com/geoserver/Mojani/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=Mojani:Villages_Boundary&outputFormat=json";

var map = L.map("map", {}).setView([18.8655, 76.7455], 6, L.CRS.EPSG4326);

var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
});

var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var Esri_WorldImagery = L.tileLayer(
    "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", {
    attribution: "Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community"
}
);

var baseURL = "https://info.dpzoning.com/geoserver/Mojani/wms";

var Villages_Boundary = L.tileLayer.wms(baseURL, {
    layers: "Villages_Boundary",
    format: "image/png",
    transparent: true,
    tiled: true,
    version: "1.1.0",
    opacity: 1,
});

var Taluka_Boundary = L.tileLayer.wms(baseURL, {
    layers: "Taluka_Boundary",
    format: "image/png",
    transparent: true,
    tiled: true,
    version: "1.1.0",
    opacity: 1,
}).addTo(map);

var Maharashtra_Data = L.tileLayer.wms(baseURL, {
    layers: "Maharashtra_Data",
    format: "image/png",
    transparent: true,
    tiled: true,
    version: "1.1.0",
    opacity: 1,
}).addTo(map);

var baseLayers = {
    "OpenStreetMap": osm,
    "Esri World Imagery": Esri_WorldImagery,
    "Google Satellite": googleSat,
};

var WMSlayers = {
   
    "Villages Boundary": Villages_Boundary,
    "Taluka Boundary": Taluka_Boundary,
    "Maharashtra_Data": Maharashtra_Data
};

var control = new L.control.layers(baseLayers, WMSlayers).addTo(map);
control.setPosition('bottomright');

map.zoomControl.remove();

L.control.zoom({
    position: 'bottomright'
}).addTo(map);



// form------------------------

$(document).ready(function () {
    // Filter districts based on search input
    $('#districtSearch').on('input', function () {
        var searchText = $(this).val().toLowerCase();
        $('#input1 option').each(function () {
            var optionText = $(this).text().toLowerCase();
            if (optionText.indexOf(searchText) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Filter talukas based on search input
    $('#talukaSearch').on('input', function () {
        var searchText = $(this).val().toLowerCase();
        $('#input2 option').each(function () {
            var optionText = $(this).text().toLowerCase();
            if (optionText.indexOf(searchText) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Filter villages based on search input
    $('#villageSearch').on('input', function () {
        var searchText = $(this).val().toLowerCase();
        $('#input3 option').each(function () {
            var optionText = $(this).text().toLowerCase();
            if (optionText.indexOf(searchText) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Populate districts on modal show
    $('#exampleModal').on('shown.bs.modal', function () {
        populateDistricts();
    });
});

var highlightLayer; // Layer for highlighted area

function populateDistricts() {
    var url = "https://info.dpzoning.com/geoserver/Mojani/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=Mojani:District_Boundary&outputFormat=json";

    if (districtCache[url]) {


        // Use cached data if available
        handleDistrictData(districtCache[url]);
    } else {
        console.time('fetchDistricts');
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                console.timeEnd('fetchDistricts');
                districtCache[url] = data; // Cache the fetched data
                handleDistrictData(data);
            },
            error: function (xhr, status, error) {
                console.error('Error fetching districts:', error);
            }
        });
    }
}

function handleDistrictData(data) {
    var districts = [];
    data.features.forEach(function (feature) {
        var districtName = feature.properties.district;
        if (districtName && districts.indexOf(districtName) === -1) {
            districts.push(districtName);
        }
    });

    // Sort districts alphabetically
    districts.sort();

    var districtSelect = $('#input1');
    districtSelect.empty();
    districts.forEach(function (district) {
        districtSelect.append($('<option></option>').attr('value', district).text(district));
    });

    // Trigger initial search input filtering for districts
    $('#districtSearch').trigger('input');

    // Call function to populate taluka dropdown based on selected district
    districtSelect.change(populateTalukas);
}

// Function to populate taluka dropdown based on selected district
function populateTalukas() {
    var layername = "Mojani:Taluka_Boundary"
    var url = `https://info.dpzoning.com/geoserver/Mojani/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=${layername}&outputFormat=json`;

    var selectedDistrict = $('#input1').val();

    const filter = `district IN ('${selectedDistrict}')`;

    if (filter) {
        url += "&CQL_FILTER=" + encodeURIComponent(filter);
    }


    // var url = geoURLt + `&CQL_FILTER=district IN ('${selectedDistrict}' )`;
    console.log(url, selectedDistrict, "geoURLt,selectedDistrict")
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            var talukas = [];
            data.features.forEach(function (feature) {
                var talukaName = feature.properties.taluka; // Adjust property name here
                if (talukaName && talukas.indexOf(talukaName) === -1) {
                    talukas.push(talukaName);
                }
            });

            // Sort talukas alphabetically
            talukas.sort();

            var talukaSelect = $('#input2');
            talukaSelect.empty();
            talukas.forEach(function (taluka) {
                talukaSelect.append($('<option></option>').attr('value', taluka).text(taluka));
            });
            // Trigger initial search input filtering for talukas
            $('#talukaSearch').trigger('input');
            // Call function to populate village dropdown based on selected taluka
            talukaSelect.change(populateVillages);

            // Fit map to selected district's bounds
            fitMapToBounds(data); // Assuming data here contains the features for talukas
        },
        error: function (xhr, status, error) {
            console.error('Error fetching talukas:', error);
        }
    });
}

// Function to populate village dropdown based on selected taluka
function populateVillages() {
    var urlls = "https://info.dpzoning.com/geoserver/Mojani/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=Mojani:Villages_Boundary&outputFormat=json";
    var selectedTaluka = $('#input2').val();
    var selectedDistrict = $('#input1').val(); // Also get the selected district for precise filtering
   
    const filter = `district IN ('${selectedDistrict}') AND taluka IN ('${selectedTaluka}') `;
    


    if (filter) {
        urlls += "&CQL_FILTER=" + encodeURIComponent(filter);
    }

console.log(urlls,"villageurl")
    $.ajax({
        url: urlls,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            var villages = [];
            data.features.forEach(function (feature) {
                var villageName = feature.properties.village; // Adjust property name here
                if (villageName && villages.indexOf(villageName) === -1) {
                    villages.push(villageName);
                }
            });

            // Sort villages alphabetically
            villages.sort();

            var villageSelect = $('#input3');
            villageSelect.empty();
            villages.forEach(function (village) {
                villageSelect.append($('<option></option>').attr('value', village).text(village));
            });

            // Trigger initial search input filtering for villages
            $('#villageSearch').trigger('input');
            // Fit map to selected taluka's bounds
            villageSelect.change(function () {
                var selectedVillage = $(this).val();
                
                // if (selectedVillage) {
                    var urlls = "https://info.dpzoning.com/geoserver/Mojani/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=Mojani:Villages_Boundary&outputFormat=json";
                    var filter = `district IN ('${selectedDistrict}') AND taluka IN ('${selectedTaluka}') AND village IN ('${selectedVillage}')`;
                    urlls += "&CQL_FILTER=" + encodeURIComponent(filter);
                // }
                console.log(urlls)
                var villageUrl = urlls
                $.ajax({
                    url: villageUrl,
                    type: 'GET',
                    dataType: 'json',
                    success: function (villageData) {
                        fitMapToBounds(villageData);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching village:', error);
                    }
                });
            });

            fitMapToBounds(data); // Assuming data here contains the features for villages
        },
        error: function (xhr, status, error) {
            console.error('Error fetching villages:', error);
        }
    });
}

// Function to fit map to bounds of selected features and highlight area
function fitMapToBounds(data) {
    var bounds = new L.LatLngBounds();

    // Assuming each feature has a geometry property with coordinates
    data.features.forEach(function (feature) {
        var geometry = feature.geometry;

        // Handle different types of geometry (e.g., MultiPolygon, Polygon)
        if (geometry.type === 'Polygon') {
            addPolygonToBounds(geometry.coordinates);
        } else if (geometry.type === 'MultiPolygon') {
            geometry.coordinates.forEach(function (coords) {
                addPolygonToBounds(coords);
            });
        }
    });

    // Function to add polygon coordinates to bounds
    function addPolygonToBounds(coords) {
        coords[0].forEach(function (point) {
            bounds.extend([point[1], point[0]]); // Leaflet expects [latitude, longitude]
        });
    }

    // Remove previous highlight layer if it exists
    if (highlightLayer) {
        map.removeLayer(highlightLayer);
    }

    // Create new highlight layer with blue border
    highlightLayer = L.geoJSON(data, {
        // style: 'highlight'

    }).addTo(map);

    // Fit the map to the bounds
    map.fitBounds(bounds);
}

// Initial population of districts
populateDistricts();


function handleFileUpload(input, targetId) {
    const files = input.files;
    const targetDiv = document.getElementById(targetId);
    targetDiv.innerHTML = '';

    for (const file of files) {
        const progressDiv = document.createElement('div');
        progressDiv.innerHTML = `<span>${file.name}</span><span id="${file.name}-progress">Uploading...</span>`;
        targetDiv.appendChild(progressDiv);

        // Simulate file upload progress
        setTimeout(() => {
            document.getElementById(`${file.name}-progress`).innerText = 'Successfully uploaded.';
        }, 2000);
    }
}
