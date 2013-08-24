'use strict';

angular.module('sgRecyclingBinApp')
.controller('MainCtrl', function($http, $scope, $timeout) {
  
  var OneMap;


  var initializeOneMap = function() {
    //    //add map with default settings
    //    var OneMap = new GetOneMap('divMain','SM');

    //    //add map with defined centerPoint
    //    var centerPoint="28968.103,33560.969"
    //    var OneMap = new GetOneMap('divMain','SM',{center:centerPoint});

    //    //add map with defined zoom level
    //    var levelNumber=8;
    //    var OneMap = new GetOneMap('divMain','SM',{level:levelNumber});

    //add map with defined center location and zoom level
    var centerPoint = "28968.103,33560.969";
    var levelNumber = 2;
    OneMap = new GetOneMap('divMain', 'SM', {level: levelNumber, center: centerPoint});
    
    $timeout(function() {
      $scope.OneMapverlay();
    }, 1000);

  };

  $scope.OneMapverlay = function() {
    var kmlPath = 'data/binMapLocation.kml';
    OneMap.overlayKML(kmlPath);
  };

  var initializeBinLocationChart = function() {

    Morris.Bar({
      element: 'bar-example',
      data: [
        {y: 'Ang Mo Kio', a: 100, b: 50.1},
        {y: 'Bedok', a: 75, b: 50.1},
        {y: 'City', a: 50, b: 50.1},
        {y: 'Clementi', a: 75, b: 50.1},
        {y: 'Hougang', a: 50, b: 50.1},
        {y: 'Tampines', a: 75, b: 50.1},
        {y: 'Queenstown', a: 100, b: 50.1},
        {y: 'Woodlands', a: 200, b: 50.1}
      ],
      xkey: 'y',
      ykeys: ['a', 'b'],
      labels: ['Number of bins', 'Collection per household']
    });
  };

  var GetCurrentLevel = function() {
    alert("Current Level:" + OneMap.map.getLevel());
  };
  
  $scope.GetBinsLocation = function() {
    $http({
      method: 'GET',
      url: 'data/binsLocation.json'
    }).success(function(xhrResponse) {
      $scope.recyclingBinLocations = xhrResponse;
    });
  };

  (function() {
    initializeOneMap();
    initializeBinLocationChart();
    $scope.GetBinsLocation();
  })();

});
