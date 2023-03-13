/*
 * Code is refered from jvectormap and customised based on needs
 * https://github.com/bjornd/jvectormap
 */

/* SVG Map config. */
const MAP_CONFIG = {
  insets: [
  {
    width: 900,
    top: 0,
    height: 440.70631074413296,
    bbox: [
    {
      y: -12671671.123330014,
      x: -20004297.151525836 },

    {
      y: 6930392.025135122,
      x: 20026572.39474939 }],


    left: 0 }],


  height: 440.70631074413296,
  projection: {
    type: 'mill',
    centralMeridian: 11.5 },

  width: 900.0 };



/* Functions to calculate x and y posiiton on the map. */
function mill(lat, lng, c) {
  const radius = 6381372;
  const radDeg = Math.PI / 180;
  return {
    x: radius * (lng - c) * radDeg,
    y: -radius * Math.log(Math.tan((45 + 0.4 * lat) * radDeg)) / 0.8 };

}

function getInsetForPoint(x, y) {
  const configInsets = MAP_CONFIG.insets;
  let i = 0;
  let box = [];
  for (i = 0; i < configInsets.length; i += 1) {
    box = configInsets[i].bbox;
    if (x > box[0].x && x < box[1].x && y > box[0].y && y < box[1].y) {
      return configInsets[i];
    }
  }
  return {};
}

/* A class that creates renders map and markers based on lat and lang. */
class WorldMap {
  constructor() {
    this.markers = [];
    this.transX = 0;
    this.transY = 0;
    this.scale = 1;
    this.baseTransX = 0;
    this.baseTransY = 0;
    this.baseScale = 1;
    this.width = 0;
    this.height = 0;
    this.defaultWidth = MAP_CONFIG.width;
    this.defaultHeight = MAP_CONFIG.height;
    this.container = jQuery('.world-map-graph-vector');

    const map = this;
    this.onResize = function () {
      map.updateSize();
    };
    let resizeTimer;
    jQuery(window).on('resize', () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(map.onResize, 20);
    });
    this.updateSize();
  }

  setMarkers(markers) {
    this.markers = markers;
  }

  updateSize() {
    this.width = this.container.get(0).getBoundingClientRect().width;
    this.height = this.container.get(0).getBoundingClientRect().height;
    this.resize();
    this.applyTransform();
  }

  resize() {
    const curBaseScale = this.baseScale;
    if (this.width / this.height > this.defaultWidth / this.defaultHeight) {
      this.baseScale = this.height / this.defaultHeight;
      const tempVal = this.width - this.defaultWidth * this.baseScale;
      this.baseTransX = Math.abs(tempVal) / (2 * this.baseScale);
    } else {
      this.baseScale = this.width / this.defaultWidth;
      const tempVal = this.height - this.defaultHeight * this.baseScale;
      this.baseTransY = Math.abs(tempVal) / (2 * this.baseScale);
    }
    this.scale *= this.baseScale / curBaseScale;
    this.transX *= this.baseScale / curBaseScale;
    this.transY *= this.baseScale / curBaseScale;
  }

  applyTransform() {
    let maxTransX = 0;
    let maxTransY = 0;
    let minTransX = 0;
    let minTransY = 0;

    if (this.defaultWidth * this.scale <= this.width) {
      maxTransX = (this.width - this.defaultWidth * this.scale) / (2 * this.scale);
      minTransX = (this.width - this.defaultWidth * this.scale) / (2 * this.scale);
    } else {
      maxTransX = 0;
      minTransX = (this.width - this.defaultWidth * this.scale) / this.scale;
    }

    if (this.defaultHeight * this.scale <= this.height) {
      maxTransY = (this.height - this.defaultHeight * this.scale) / (2 * this.scale);
      minTransY = (this.height - this.defaultHeight * this.scale) / (2 * this.scale);
    } else {
      maxTransY = 0;
      minTransY = (this.height - this.defaultHeight * this.scale) / this.scale;
    }

    if (this.transY > maxTransY) {
      this.transY = maxTransY;
    } else if (this.transY < minTransY) {
      this.transY = minTransY;
    }
    if (this.transX > maxTransX) {
      this.transX = maxTransX;
    } else if (this.transX < minTransX) {
      this.transX = minTransX;
    }
    if (this.markers) {
      this.repositionMarkers();
    }
  }

  repositionMarkers() {
    for (let i = 0; i < this.markers.length; i += 1) {
      const point = this.getMarkerPosition(this.markers[i]);
      if (point !== false) {
        const marker = jQuery(`.world-map-marker[data-index="${this.markers[i].key}"]`);
        marker.attr({
          cx: point.x,
          cy: point.y });

      }
    }
  }

  latLngToPoint(lat, long) {
    let point = {};
    const proj = MAP_CONFIG.projection;
    let inset = [];
    let box = [];

    let lng = long;
    if (lng < -180 + proj.centralMeridian) {
      lng += 360;
    }

    point = mill(lat, lng, proj.centralMeridian);

    inset = getInsetForPoint(point.x, point.y);
    if (inset) {
      box = inset.bbox;

      point.x = (point.x - box[0].x) / (box[1].x - box[0].x) * inset.width * this.scale;
      point.y = (point.y - box[0].y) / (box[1].y - box[0].y) * inset.height * this.scale;

      return {
        x: point.x + this.transX * this.scale + inset.left * this.scale,
        y: point.y + this.transY * this.scale + inset.top * this.scale };

    }
    return false;
  }

  getMarkerPosition(markerConfig) {
    if (MAP_CONFIG.projection) {
      const lat = markerConfig.latLng[0];
      const lng = markerConfig.latLng[1];
      return this.latLngToPoint(lat || 0, lng || 0);
    }
    return {
      x: markerConfig.coords[0] * this.scale + this.transX * this.scale,
      y: markerConfig.coords[1] * this.scale + this.transY * this.scale };

  }

  createMarkers() {
    const markerWrap = jQuery('.marker-wrap');
    _.map(this.markers, marker => {
      if (_.has(marker, 'latLng') && _.isArray(marker.latLng) && marker.latLng.length === 2) {
        const point = this.getMarkerPosition(marker);
        const circleEle = jQuery('.marker-template circle.world-map-marker:not(.selected-outer-circle)').clone();
        circleEle.attr({
          'data-index': marker.key,
          cx: point.x,
          cy: point.y });

        circleEle.appendTo(markerWrap);
      }
    });
  }}


/* JSON to add markers and select countries. */
const CONTINENT_MARKERS = [
{
  key: 1,
  latLng: [-15, -50],
  name: 'Brasil',
  countries: ['BR'],
  default: false,
  percentage: 20 
},
{
  key: 2,
  latLng: [60, -110],
  name: 'Canadá',
  countries: ['CA'],
  default: false,
  percentage: 33.5 
},
{
  key: 3,
  latLng: [-25, 135],
  name: 'Austrália',
  countries: ['AU'],
  default: false,
  percentage: 11.5 
}];



/* On click on a particular marker based in the percentage the marker is bloted,
 * its radius is calculated based ont he map size.
 */
function sizeOfMarkerBasedOnPercentage(percentage) {
  const parentEle = jQuery('.world-map-svg');
  const totalHeight = parentEle.height();
  let radius = totalHeight / 3;
  radius = radius * percentage / 100;
  return radius;
}

/* Window resize function for handling responsive. */
function windowResize() {
  const mainSection = jQuery('.world-map-section');
  const windowWidth = mainSection.find('.world-map-container').width() - 30;
  const defaultWidth = 900;
  const calculatedWidthHeight = windowWidth / defaultWidth;
  const vectorGraphEle = mainSection.find('.world-map-graph-vector');
  vectorGraphEle.css('transform', `scale(${calculatedWidthHeight}, ${calculatedWidthHeight})`);
  if (vectorGraphEle) {
    const parentEle = mainSection.find('.world-map-svg');
    parentEle.removeClass('d-none');
    parentEle.css('height', `${vectorGraphEle.get(0).getBoundingClientRect().height}px`);
  }
  const marker = mainSection.find('.world-map-marker.selected-outer-circle');
  const percentage = parseInt(marker.attr('data-percentage'), 10);
  const radius = sizeOfMarkerBasedOnPercentage(percentage);
  marker.css('r', `${radius}`);
}

function selectMarkerOnGraph(obj) {
  const mainSection = jQuery('.world-map-section');
  const vectorGraph = mainSection.find('.world-map-graph-vector path');
  const markerWrap = mainSection.find('.marker-wrap');
  jQuery('.world-map-marker').removeClass('selected');
  mainSection.find('.marker-wrap circle.selected-outer-circle').remove();
  vectorGraph.removeClass('selected');
  const selectedCountries = _.filter(vectorGraph, graph => {
    const bool = _.includes(obj.countries, graph.getAttribute('data-code'));
    return bool;
  });
  const marker = mainSection.find(`.world-map-marker[data-index="${obj.key}"]`);
  marker.addClass('selected');
  const outerCircleEle = mainSection.find('.marker-template circle.selected-outer-circle').clone();
  const radius = sizeOfMarkerBasedOnPercentage(obj.percentage);
  outerCircleEle.attr({
    'data-index': obj.key,
    cx: marker.attr('cx'),
    cy: marker.attr('cy'),
    'data-percentage': obj.percentage }).
  css('r', `${radius}`);
  outerCircleEle.appendTo(markerWrap);
  mainSection.find(selectedCountries).addClass('selected');
  mainSection.find('.continent-discription-wrap .continent-name').text(obj.name);
  mainSection.find('.continent-discription-wrap .stats-number.percentage').text(`${obj.percentage} Percentage`);
}

function initialSelect() {
  const obj = _.find(CONTINENT_MARKERS, ['default', true]);
  if (obj) {
    selectMarkerOnGraph(obj);
  }
}

function onMarkerClick() {
  const marker = jQuery('circle.world-map-marker');
  marker.on('click', e => {
    const clickedEle = jQuery(e.target);
    let key = clickedEle.attr('data-index');
    key = parseInt(key, 10);
    const obj = _.find(CONTINENT_MARKERS, ['key', key]);
    selectMarkerOnGraph(obj);
    if (obj.key === 1) {
      console.log(`O meu valor é ${obj.key}`);
    } else if (obj.key === 2) {
      console.log(`O meu valor é ${obj.key}`);
    } else if (obj.key === 3) {
      console.log(`O meu valor é ${obj.key}`);
    }
  });
}

function renderMarkers() {
  const worldMap = new WorldMap();
  worldMap.setMarkers(CONTINENT_MARKERS);
  worldMap.createMarkers();
  onMarkerClick();
}

windowResize();
renderMarkers();
initialSelect();
jQuery(window).on('resize', windowResize);