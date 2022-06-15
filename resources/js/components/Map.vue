<template>
    <div>
        <div class="p-2">
            <div id="map" class="w-96 h-96" />
            <MoreInformationModal ref="modal" :isSearchedLocation="this.isSearchedLocation" :place="this.searchPlace" />
        </div>
    </div>
</template>

<style>
canvas {
    width: 700px !important;
    height: 700px !important;
}

.w-96 {
    width: 661px !important;
}

.h-96 {
    height: 660px !important;
}
</style>

<script>

import 'mapbox-gl/dist/mapbox-gl.css';
import mapboxgl from 'mapbox-gl';
import { EventBus } from '../event/event-bus';
import MoreInformationModal from '../components/MoreInformationModal'
export default {

    name: "Map",
    components: {
        MoreInformationModal
    },
    data() {
        return {
            centerCordinates: [],
            places: [],
            searchPlace: {},
            isSearchedLocation: false
        }
    },

    created() {
        EventBus.$on('clicked', this.clickHandler);
    },

    methods: {
        clickHandler(res) {

            this.centerCordinates = []
            this.searchPlace = res.searchPlace;
            this.searchedLocation = true
            this.centerCordinates.push(res.searchPlace.lon)
            this.centerCordinates.push(res.searchPlace.lat)
            this.places = res.nearByPlaces;
            this.showMap()
        },

        showMap() {
            let map = null
            // https://docs.mapbox.com/mapbox-gl-js/example/add-a-marker/
            mapboxgl.accessToken = process.env.MIX_VUE_APP_MAP_BOX_ACCESS_TOKEN;
            map = new mapboxgl.Map({
                container: "map",
                style: "mapbox://styles/mapbox/streets-v11",
                center: this.centerCordinates,
                zoom: 12
            })

            const nav = new mapboxgl.NavigationControl();
            map.addControl(nav, "top-right");


            // create the popup for search location
            const popup = new mapboxgl.Popup({ offset: 25 }).setHTML(`
                    <div class="modal-body">
                            <p>Name:  ${this.searchPlace.name} </p>
                            <p>population:  ${this.searchPlace.population}</p>
                            <p>timezone:  ${this.searchPlace.timezone}</p>
                            <p>lat:  ${this.searchPlace.lat}</p>
                            <p>lon:  ${this.searchPlace.lon}</p>
                    </div>
            `)

            // Create a search location Marker, colored black, rotated 45 degrees.
            const marker = new mapboxgl.Marker({ color: 'black', rotation: 45 })
                .setLngLat(this.centerCordinates)
                .setPopup(popup)
                .addTo(map);

            // show the nearBy places marker.
            this.places.forEach(function (place) {
                const popup = new mapboxgl.Popup({ offset: 25 }).setHTML(`
                    <div class="modal-body">
                            <p>Name:  ${place.name} </p>
                            <p>Distance:  ${place.dist}</p>
                            <p>kinds:  ${place.kinds}</p>
                            <p>Rating:  ${place.rate}</p>
                            <p>lat:  ${place.point.lat}</p>
                            <p>lon:  ${place.point.lon}</p>
                    </div>
            `)
                new mapboxgl.Marker({ color: place.color, rotation: 45 })
                    .setLngLat([place.point.lon, place.point.lat])
                    .setPopup(popup)
                    .addTo(map);
            });
        }


    }

}
</script>
