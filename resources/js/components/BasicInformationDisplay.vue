<template>
    <div>
        <div id="info" class="alert alert-primary s-pad-wid">
            <p>{{ name }}, {{ country }} | {{ radius }}km radius | total {{ totalPlaces }} places</p>
        </div>
    </div>
</template>

<style>
p {
    float: center;
    /* margin-top: 0; */
    /* margin-bottom: 1rem; */
    padding: 4px;
    margin-left: 10px;
}

.s-pad-wid {
    margin: -22px;
    width: 62%;
    float: center;
    margin-left: 131px;
    padding: 7px;
    height: 51px;
}
</style>

<script>

export default {
    name: "BasicInformation",
    props: [
        'name',
        "country",
        "totalPlaces",
        "radius"
    ],

}
</script>
