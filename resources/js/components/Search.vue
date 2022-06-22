<template>
    <div>
        <div class="container">
            <center>
                <form id="search_form" class="input-group mb-4 border p-1" @submit.prevent="enterPressed">
                    <div class="input-group-prepend border-0">
                        <button id="button-search" type="submit" class="btn btn-link ">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                    <input id="textbox" name="search" type="search" placeholder="Region, city, village, etc. (e.g. Amsterdam)"
                        aria-describedby="button-search" class="form-control bg-none border-0" v-model="searchTerm" />
                </form>
            </center>
        </div>

        <BasicInformationDisplay v-if="this.places.totalPlaces" :name="this.places.searchPlace.name"
            :country="this.places.searchPlace.country" :totalPlaces="this.places.totalPlaces" :radius="10">
        </BasicInformationDisplay>

        <PlacesList v-if="this.places.totalPlaces" :places="this.places.searchResult">
        </PlacesList>

        <div class="row" v-if="this.places.totalPlaces">
            <div class="col-12 col-lg-5">
                <div id="list" class="list-group"></div>
                <nav class="text-center">
                    <button id="next_button" type="button" class="btn btn-primary" v-on:click="next">
                        Next {{ this.recordLimit }}
                    </button>
                </nav>
            </div>
            <div class="col-12 col-lg-7">
                <div id="poi" class="alert"></div>
            </div>
        </div>
    </div>
</template>

<style>
.container {
    padding: 10px !important;
}

.input-group {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -ms-flex-align: stretch;
    align-items: stretch;
    width: 63%;
}
</style>

<script>
import { EventBus } from '../event/event-bus';
import BasicInformationDisplay from './BasicInformationDisplay';
import PlacesList from "./PlacesList"

export default {
    name: "Search",
    components: {
        BasicInformationDisplay,
        PlacesList
    },
    data() {
        return {
            places: {
                searchPlace: [],
                searchResult: [],
                totalPlaces: 0
            },
            searchTerm: null,
            recordLimit: parseInt(process.env.MIX_VUE_APP_LIMIT),
            offset: 0
        }
    },

    methods: {

        enterPressed(e) {
            e.preventDefault();
            if (this.searchTerm) {
                this.resetVar()
                this.fetchData()
            }
            else {
                alert("Please enter some text");
            }
            return false;
        },

        resetVar() {
            this.offset = 0
            console.log(typeof (this.offset))
            this.places.searchPlace = []
            this.places.searchResult = []
        },

        async fetchData() {

            try {
                let res = await axios.get('/locations', {
                    params: {
                        searchTerm: this.searchTerm,
                        offset: this.offset,
                        _token: document.querySelector("meta[name='csrf-token']").getAttribute('content')
                    }
                });

                res = await res.data.data;
                this.places.searchPlace = res.searchedLocation;
                this.places.totalPlaces = res.total
                let nearByPlaces = res.nearByLocations;
                
                for (let place of nearByPlaces) {

                    place.kinds = place.kinds.split(',')[0]
                    place.color = this.getColor(place.kinds)
                    this.places.searchResult.push(place);
                };

                // emit the global event
                EventBus.$emit('clicked', {
                    searchPlace: this.places.searchPlace,
                    nearByPlaces: this.places.searchResult
                });
            } catch (error) {
                alert(error)
            }
        },

        next() {
            this.offset = parseInt(this.offset) + this.recordLimit
            this.fetchData()
        },

        getColor(kind) {
            let kinds = this.getListOfkinds()
            let color = "crimson"
            for (const key in kinds) {
                if (key === kind) {
                    color = kinds[key];
                    return color;
                }
            }
            return color;
        },

        getListOfkinds() {
            return {
                architecture: "red",
                historic: "brown",
                historic: "cyan",
                historic_architecture: "cyan",
                sports: "green",
                natural: "maroon",
                cultural: "purple",
                industrial: "aqua",
                industrial_facilities: "aqua",
                amusements: "blue",
                parks: "navy",
                accomodations: "yellow",
                adult: "olive",
                religion: "darkgray",
                palaces: "darkorange"
            }
        }
    },

}


  // console.log(respp.data)
            //  let searchPlace = await axios('https://api.opentripmap.com/0.1/en/places/geoname?apikey=5ae2e3f221c38a28845f05b6b8508275576c53e14be83536536cac18&name=' + this.searchTerm);
            //searchPlace = await searchPlace.data;
            //let nearByPlaces = await axios('https://api.opentripmap.com/0.1/en/places/radius?apikey=5ae2e3f221c38a28845f05b6b8508275576c53e14be83536536cac18&radius=10000&limit=' + this.recordLimit + '&offset=' + this.offset + '&lon=' + searchPlace.lon + '&lat=' + searchPlace.lat + '&rate=2&format=json')
            //nearByPlaces = await nearByPlaces.data
</script>


