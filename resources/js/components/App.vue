<template>
    <div>
        <Header></Header>
        <div class="row">
            <div style="width: 50%; ">
                <Search></Search>
            </div>
            <div style="width: 50%;">
                <Map></Map>
            </div>
        </div>
    </div>
</template>

<script>
import Map from '../components/Map'
import Search from '../components/Search'
import Header from '../components/Header'

export default {
    name: "App",
    components: {
        Map,
        Search,
        Header
    },
}
</script>

