<template>
    <div class="m-4">

        <div>
            <button class="btn btn-primary" v-on:click="this.showLights">Lights</button>
            <button class="btn btn-primary" v-on:click="this.showTest">Test</button>
            <hr>
        </div>

        <div id="welcome" class="text-center">
            <h1>Welcome!</h1>
            <h3>Through this web application you can monitor all of your connected smart home devices.</h3>
            <hr>
        </div>

        <div id="display">
            <lights id="lightsElement" hidden></lights>
            <test id="testElement" hidden></test>
        </div>

    </div>
</template>

<script>
import Lights from './Lights';
import Test from './Test';

export default {
    name: 'app',
    data() {
        return {

        }
    },
    components: {
        Lights,
        Test
    },
    methods: {
        hideElements() {
            document.getElementById('welcome').hidden = true;
            document.getElementById('lightsElement').hidden = true;
            document.getElementById('testElement').hidden = true;
        },
        showLights() {
            this.hideElements();
            document.getElementById("lightsElement").hidden = false;
        },
        showTest() {
            this.hideElements();
            document.getElementById("testElement").hidden = false;
        }
    }
}
</script>

<style lang="stylus" scoped>

</style>