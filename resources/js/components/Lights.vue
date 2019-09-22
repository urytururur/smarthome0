<template>
    <div>
        <div v-for="light in lights" v-bind:key="light.id">
            <h3 class="text-center">Light {{lights.indexOf(light) + 1}}</h3>
            <div class="row">
                <div class="col-xs-6 w-50 pl-3">
                    <p><strong>On: </strong>{{light.on}}</p>
                    <p><strong>Brightness: </strong>{{light.bri}}</p>
                </div>
                <div class="col-xs-6 w-50 pr-3">
                    <p class="text-right"><strong>Hue: </strong>{{light.hue}}</p>
                    <p class="text-right"><strong>Saturation: </strong>{{light.sat}}</p>
                </div>
            </div>
            <hr>
        </div>
    </div>

</template>

<script>
export default {
    data() {
        return {
            lights: []
        }
    },
    created() {
        this.getLights();
        setInterval(this.getLights, 3000);
    },
    methods: {
        /*
        PURPOSE OF "getLights":
        1. getting lights from servers database
        3. updating the web applications data-function for display in web application
        */
        getLights() {

            //getting lights from database
            console.log("GETTING LIGHTS FROM DATABASE (WAN)");
            fetch('http://155.4.151.137/smarthome0/public/api/lights')
            .then(res => res.json())
            .then(res => {

                let lightsFromRouter = Object.values(res.data);

                //updating vue data-function for displaying state in web application
                this.lights = lightsFromRouter;

                for(let i = 0; i < this.lights.length; i++)
                {
                    if(this.lights[i].on == 0)
                    {
                        this.lights[i].on = "false";
                    }
                    if(this.lights[i].on == 1)
                    {
                        this.lights[i].on = "true";
                    }
                }
            });
        },
    }
}
</script>

<style lang="stylus" scoped>

</style>