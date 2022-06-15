<template>
    <div class="p-25">
        <h3 class="p-3 text-center"></h3>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Distance (Meters)</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(place, index) in places" :key="index">
                    <td>{{ place.name }}</td>
                    <td>{{ place.dist }}</td>
                </tr>
            </tbody>
        </table>
    </div>

</template>

<style>
.p-25 {
    padding-left: 25px !important;
}
</style>

<script>
export default {
    props: [
        "places"
    ],
    data() {
        return {
        };
    }
};
</script>